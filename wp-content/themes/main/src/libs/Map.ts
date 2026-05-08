import { LngLat, YMap, YMapMarkerProps } from "@yandex/ymaps3-types";

interface IMapConstructorProps {
    container: HTMLElement;
    center?: LngLat;
    zoom?: number;
}

export default class Map {
    container: HTMLElement;
    ymap?: YMap | undefined;
    constructor({ container, center, zoom }: IMapConstructorProps) {
        if (!container) throw new Error("Контейнер не определён");

        this.container = container;
        this.createMap(center, zoom);
    }

    private static key = "ca4ab438-ce96-431f-9eb5-8441c2a35bb3";
    private static ymapIsReady = false;
    private static isLoading = false;
    private static loader: Promise<boolean>;

    static loadAPI() {
        if (!this.isLoading && !this.ymapIsReady) {
            this.loader = this.startLoad();
            return this.loader;
        } else return this.loader;
    }

    private static startLoad() {
        return new Promise<boolean>((resolve) => {
            this.isLoading = true;
            if (Map.ymapIsReady) {
                this.isLoading = false;
                resolve(true);
                return;
            }

            const script = document.createElement("script");
            script.src = `https://api-maps.yandex.ru/v3/?apikey=${this.key}&lang=ru_RU`;
            script.addEventListener("load", async () => {
                await ymaps3.ready;
                Map.ymapIsReady = true;
                this.isLoading = false;
                resolve(true);
            });
            document.body.append(script);
        });
    }

    static createDefaultMarker() {
        const marker = document.createElement("div");
        marker.innerHTML = `
        <svg width="34" height="49" viewBox="0 0 34 49" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 17.002C0 26.3919 7.61203 34.0039 17.002 34.0039V48.0703C20.4268 45.7463 26.298 39.5081 29.4782 34.0039C32.6584 28.4997 34.0039 22.5062 34.0039 17.002C34.0039 7.61203 26.3919 0 17.002 0C7.61203 0 0 7.61203 0 17.002Z" fill="#EEBC6B"/>
        <circle cx="17.0033" cy="16.5124" r="10.8861" fill="white"/>
        <path d="M18.3022 9H15.6953V32.5H18.3022V9Z" fill="#EEBC6B"/>
        <path d="M24.1431 16.925H9.85742V19.5319H24.1431V16.925Z" fill="#EEBC6B"/>
        <path d="M20.8055 12.2306H13.1934V14.8375H20.8055V12.2306Z" fill="#EEBC6B"/>
        </svg>
        `;
        marker.className = "default-map-marker";
        marker.style.cssText = "cursor: pointer; transform: translate(-50%, -100%); width: 34px; height: 48px;";

        return marker;
    }

    createMap(center = [37.617635, 55.755814] as LngLat, zoom = 15) {
        this.ymap = new ymaps3.YMap(
            this.container,
            {
                location: {
                    center,
                    zoom,
                },
            },
            [
                new ymaps3.YMapDefaultSchemeLayer({
                    theme: "dark"
                }),
                new ymaps3.YMapDefaultFeaturesLayer({ zIndex: 1800 }),
            ]
        );
    }

    setLocation(center: LngLat, zoom = 15) {
        if (!this.ymap) return;

        this.ymap?.setLocation({
            center,
            zoom,
        });
    }

    addMarker(
        coordinates: LngLat,
        content: HTMLElement,
        props?: Partial<YMapMarkerProps>
    ) {
        if (!this.ymap) return;

        const marker = new ymaps3.YMapMarker(
            {
                coordinates,
                draggable: false,
                ...props,
            },
            content
        );

        this.ymap.addChild(marker);
    }

    addMarkers(coordinates: LngLat[], content: () => HTMLElement, props?: Partial<YMapMarkerProps>) {
        coordinates.forEach((coords) => {
            this.addMarker(coords, content(), props);
        });
    }

    getYMap(): YMap {
        if (!this.ymap)
            throw new Error("YMap is not loaded");

        return this.ymap
    }
}
