import { showModal } from "./modal"

export default (modalID) => ({
    init() {
        this.$root.addEventListener("click", () => showModal(modalID))
    }
})