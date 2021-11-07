const path = require('path');

module.exports = {
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: "ts-loader",
                exclude: /node_modules/
            }
        ]
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
        extensions: ["*", ".js", ".jsx", ".ts", ".tsx"]
    },
};
