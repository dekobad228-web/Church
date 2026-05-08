export default () => ({
    async init() {
        import("@fancyapps/ui/dist/fancybox/fancybox.css");
        const { Fancybox } = await import("@fancyapps/ui");

        Fancybox.bind(this.$root, "[data-fancybox]", {
            Html: {
                videoTpl:
                    '<video class="fancybox__html5video" playsinline controls controlsList="nodownload" src="{{src}}" type="{{format}}" poster="{{poster}}"></video>',
            },
        });
    },
});
