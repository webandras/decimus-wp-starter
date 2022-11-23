import axios from "axios";

export default {
    data: () => ({
        apiResponse: "", // initial loading or error messages.
        wpPosts: [], // REST API response data goes here.
        /* eslint-disable no-undef */
        wpData, // global data made available via wp_localize_script.
        isDataAvailable: false,
    }),

    computed: {
        // computed property to get filtered data based on the search key.
        filteredResults() {
            const pattern = new RegExp(this.searchTerm, "i"); // match keyword against post titles or excerpts.
            const filteredPosts = this.wpPosts.filter((post) => {
                return (
                    post.title.rendered.match(pattern) ||
                    post.vue_meta.custom_excerpt.match(pattern)
                );
            });

            // further filter the results based on the category filters.
            if (this.appFilters && this.appFilters.length) {
                return filteredPosts.filter((post) =>
                    post.vue_meta.term_ids.some((terms) =>
                        this.appFilters.includes(terms)
                    )
                );
            } else {
                return filteredPosts;
            }
        },
    },

    props: {
        searchTerm: {
            type: String,
            default: "",
        },
        appFilters: {
            type: Array,
            default: null,
        },
        route: {
            type: String,
            default: "posts",
        },
        fetchNow: {
            type: Number,
            default: 1,
        },
        order: {
            type: String,
            default: "desc",
        },
    },

    mounted() {
        // get posts from the WordPress REST API on component creation.
        this.fetchData();
    },

    watch: {
        // watch the prop fetchNow which changes when submit is clicked, and call the method this.fetchData()
        fetchNow: "fetchData",
        order: "fetchData",
    },

    methods: {
        // fetch posts only if fetchNow is greater than 0.
        fetchData() {
            if (0 < this.fetchNow) {
                this.isDataAvailable = false;
                this.getPosts(this.route, "wp/v2", this.order);
                this.apiResponse = " Loading... ";
            }
        },

        /* eslint-disable */
        async getPosts(route = "posts", namespace = "wp/v2", order = "desc") {
            try {
                /* Note: the per_page argument is capped at 100 records by the REST API.
                 * https://developer.wordpress.org/rest-api/using-the-rest-api/pagination/
                 */
                const restURL = process.env.NODE_ENV === 'development' ? process.env.VUE_APP_REST_API_PATH : this.wpData.rest_url;
                const postsPerPage = 100; // default is 10.
                const fields = "id,title,date_gmt,link,excerpt,vue_meta"; // retrieve data for specific fields only.

                // send an initial request and await the response to get the total number of posts.
                const response = await axios(
                    `${restURL}/${namespace}/${route}?per_page=${postsPerPage}&page=1&_fields=${fields}&order=${order}`
                );
                // since partial data is already available from this response, make it available.
                this.wpPosts = response.data;
                this.isDataAvailable = true;
                /*
                 * calculate total number of required API requests using the header fields from the response.
                 * headers['x-wp-total']: Total WordPress Posts
                 * headers['x-wp-totalpages'] Total number of pages based on the per_page param.
                 */
                const wpTotalPages = Math.ceil(
                    response.headers["x-wp-total"] / postsPerPage
                );
                // check & get additional posts but restrict to 1000 posts when per_page is 100. Modify this per your needs.
                const promises = [];
                for (let page = 2; page <= wpTotalPages && 10 >= page; page++) {
                    promises.push(
                        // save the promise returned by the axios requests.
                        axios(
                            `${restURL}/${namespace}/${route}?per_page=${postsPerPage}&page=${page}&_fields=${fields}`
                        )
                    );
                }
                // Await all promises to return before rendering the data.
                const wpData = await Promise.all(promises);
                wpData.map((post) => this.wpPosts.push(...post.data)); // post.data returns an array.
                // using the ES6 Spread operator ...post.data to push items of the post.data array instead of the array itself.

                /* OR
                 * render data as soon as it becomes available.
                 */
                // promises.map( posts => {
                // 	posts.then( post => {
                // 		this.wpPosts.push( ...post.data );
                // 		if ( ! this.isDataAvailable ) {
                // 			this.isDataAvailable = true;
                // 		}
                // 	});
                // });

                // all posts are retrieved at this point.
            } catch (error) {
                const msg = 'The request could not be processed!';
                this.apiResponse = `${msg} <br> <strong>${error}</strong> `;
            }
        },


    },

}

