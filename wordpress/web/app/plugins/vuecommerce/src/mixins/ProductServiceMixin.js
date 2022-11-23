import axios from "axios";
import sortArray from "sort-array";

export default {
    data() {
        return {
            apiResponse: "", // initial loading or error messages.
            wpProducts: [], // REST API response data goes here.
            /* eslint-disable no-undef */
            wpData, // global data made available via wp_localize_script.
            isDataAvailable: false,
            highestPrice: 0,
            lowestPrice: 0,
        };
    },

    computed: {
        // computed property to get filtered data based on the search key.
        filteredResults() {
            const pattern = new RegExp(this.searchTerm, "i"); // match keyword against post titles or excerpts.
            let filteredProducts = this.wpProducts.filter((product) => {
                return (
                    product.name.match(pattern) || product.description.match(pattern)
                );
            });

            // further filter the results based on the category filters.
            if (this.categoryFilters && this.categoryFilters.length) {
                filteredProducts = filteredProducts.filter((product) =>
                    product.categories.some((cat) =>
                        this.categoryFilters.includes(cat.id)
                    )
                );
            }
            // filter is product is discounted
            if (this.onSale === true) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.on_sale === true
                );
            }
            // filter by max price
            if (this.maxPrice !== null) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.price <= this.maxPrice
                );
            }
            // filter by min price
            if (this.minPrice !== null) {
                filteredProducts = filteredProducts.filter(
                    (product) => product.price >= this.minPrice
                );
            }

            if (this.orderBy === "slug") {
                sortArray(filteredProducts, {by: "slug", order: "asc"});
            }

            if (this.orderBy === "price") {
                sortArray(filteredProducts, {
                    by: "numericPrice",
                    order: "asc",
                    computed: {
                        numericPrice: (product) => parseFloat(product.price),
                    },
                });
            }

            if (this.orderBy === "total_sales") {
                sortArray(filteredProducts, {
                    by: "total_sales",
                    order: "asc",
                });
            }

            return filteredProducts;
        },
    },

    props: {
        searchTerm: {
            type: String,
            default: "",
        },
        categoryFilters: {
            type: Array,
            default: null,
        },
        route: {
            type: String,
            default: "products",
        },
        fetchNow: {
            type: Number,
            default: 1,
        },
        onSale: {
            type: Boolean,
            default: false,
        },
        maxPrice: {
            type: Number,
            default: null,
        },
        minPrice: {
            type: Number,
            default: null,
        },
        order: {
            type: String,
            default: "desc",
        },
        orderBy: {
            type: String,
            default: "date",
        },
    },

    mounted() {
        // get products from the WordPress REST API on component creation.
        /* eslint-disable no-unused-vars */
        this.getProducts();
    },

    watch: {
        // watch the prop fetchNow which changes when submit is clicked, and call the method this.fetchData()
        fetchNow: "fetchData",
    },

    methods: {
        getHighestPrice() {
            this.$emit("onGettingHighestPrice", this.highestPrice);
        },
        getLowestPrice() {
            this.$emit("onGettingLowestPrice", this.lowestPrice);
        },

        // fetch products only if fetchNow is greater than 0.
        fetchData() {
            if (0 < this.fetchNow) {
                this.isDataAvailable = false;
                // wc/v3/products
                this.getProducts(this.route, "wc/v3", this.order, this.orderBy).then(() => {
                });
                this.apiResponse = "Loading... ";
            }
        },

        /* eslint-disable */
        async getProducts(
            route = "products",
            namespace = "wc/v3",
            order = "desc",
            orderBy = "date"
        ) {
            try {
                /* Note: the per_page argument is capped at 100 records by the REST API.
                 * https://developer.wordpress.org/rest-api/using-the-rest-api/pagination/
                 */
                const restURL =
                    process.env.NODE_ENV === "development"
                        ? process.env.VUE_APP_REST_API_PATH
                        : this.wpData.rest_url;
                const ck = process.env.VUE_APP_CK;
                const cs = process.env.VUE_APP_CS;
                const productsPerPage = 100; // default is 10.
                const status = "publish"; // retrieve data for published products

                // http://localhost:8080/wp-json/api/v1/token
                /*const jwtToken = "";
                const axiosConfig = {
                  headers: {
                    Authorization: "Bearer " + jwtToken,
                  },
                };*/

                // send an initial request and await the response to get the total number of posts.
                const response = await axios.get(
                    `${restURL}/${namespace}/${route}?per_page=${productsPerPage}&page=1&status=${status}&orderby=${orderBy}&order=${order}`,
                    {
                        auth: {
                            username: ck,
                            password: cs,
                        },
                    }
                );
                // since partial data is already available from this response, make it available.
                this.wpProducts = response.data;
                this.isDataAvailable = true;

                /*
                 * calculate total number of required API requests using the header fields from the response.
                 * headers['x-wp-total']: Total WordPress Posts
                 * headers['x-wp-totalpages'] Total number of pages based on the per_page param.
                 */
                const wpTotalPages = Math.ceil(
                    response.headers["x-wp-total"] / productsPerPage
                );

                // check & get additional posts but restrict to 1000 posts when per_page is 100. Modify this per your needs.
                const promises = [];

                for (let page = 2; page <= wpTotalPages && 10 >= page; page++) {
                    promises.push(
                        // save the promise returned by the axios requests.
                        axios.get(
                            `${restURL}/${namespace}/${route}?per_page=${productsPerPage}&page=${page}&status=${status}&&orderby=${orderBy}&order=${order}`,
                            {
                                auth: {
                                    username: ck,
                                    password: cs,
                                },
                            }
                        )
                    );
                }
                // Await all promises to return before rendering the data.
                const wpData = await Promise.all(promises);
                wpData.map((product) => this.wpProducts.push(...product.data)); // product.data returns an array.

                this.wpProducts.forEach((product) => {
                    const price = parseInt(product.price, 10);
                    if (price > this.highestPrice) {
                        this.highestPrice = price;
                    }
                });

                this.lowestPrice = this.highestPrice;
                this.wpProducts.forEach((product) => {
                    const price = parseInt(product.price, 10);
                    if (price < this.lowestPrice) {
                        this.lowestPrice = price;
                    }
                });

                // emit newly calculated highest possible price
                this.getHighestPrice();
                this.getLowestPrice();

                // all posts are retrieved at this point.
            } catch (error) {
                const msg = $t("The request could not be processed!");
                this.apiResponse = `${msg} <br> <strong>${error}</strong> `;
            }
        },
    },

}

