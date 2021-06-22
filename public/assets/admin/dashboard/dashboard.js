let rootEl = new Vue({
    el: '#root',
    methods: {
        logout: function() {
            window.location.href = "/logout";
        }
    },
});

