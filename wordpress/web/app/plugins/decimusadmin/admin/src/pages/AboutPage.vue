<template>
    <div class="decimus-wrapper">
        <h2 class="sub-title">About</h2>

        <div class="mb-40 w-500">
            <h3>Decimus is a developer-oriented theme.</h3>
            <p>Decimus theme fully supports <a href="https://woocommerce.com/" target="_blank"
                                               rel="noopener noreferrer">WooCommerce
                <font-awesome-icon icon="link" size="sm"/>
            </a>.
            </p>
            <p>Decimus is created by András Gulácsi 2022</p>

        </div>

        <div class="mb-40 w-500">
            <h3>Docs, issue reporting</h3>
            <p>
                Documentation is available on <a href="https://github.com/SalsaBoy990/decimus-wp-starter"
                                                 target="_blank"
                                                 rel="noopener noreferrer">GitHub
                <font-awesome-icon icon="link" size="sm"/>
            </a>
            </p>
            <p>
                Please report bugs / request new features at the GitHub issues tab.
            </p>
        </div>

    </div>
</template>

<script>
export default {
    name: "AboutPage",
    data() {
        return {};
    },
};
</script>

<style scoped>
.form-check input.form-check-input[type="radio"] {
    position: relative;
    top: 10px;
}

.mb-40 {
    margin-bottom: 40px;
}

.w-340 {
    max-width: 340px;
}

.w-400 {
    max-width: 400px;
}

.decimus-wrapper {
    position: relative;
}

.decimus-wrapper p {
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;
}

.decimus-wrapper .black-text {
    font-weight: 500;
    color: #000000;
}

.decimus-wrapper .important {
    font-weight: 500;
    font-size: 13px;
    line-height: 150%;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: rgba(0, 0, 0, 0.7);
}

.decimus-wrapper small {
    position: absolute;
    bottom: -80px;
    left: 0;
    display: block;
}

.decimus-wrapper h3 {
    font-family: Ubuntu, sans-serif;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: #000000;
    margin-top: 25px;
    margin-bottom: 20px;
}

.decimus-wrapper h3 > span.small-text {
    font-family: Ubuntu, sans-serif;
    font-style: normal;
    font-weight: normal;
    font-size: 10px;
    line-height: 11px;
    letter-spacing: 0.02em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: #000000;
}

.decimus-wrapper h2.sub-title {
    font-family: Ubuntu, sans-serif;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: rgba(0, 0, 0, 0.6);
}
</style>
