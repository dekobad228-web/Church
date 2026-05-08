import Map from "@/libs/Map"


export default (points, zoom) => ({
    center: null,
    async init() {
        if (points.length == 0)
            return;

        await Map.loadAPI();

        const map = new Map({
            container: this.$refs.mapRoot
        })
        map.setLocation(points[0], zoom)
        map.addMarkers(points, Map.createDefaultMarker)

        this.$watch('center', (value) => {
            map.setLocation(value)
        })
    },
    setLocation(coordiantes) {
        this.center = coordiantes
    }
})