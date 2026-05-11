import MicroModal from "micromodal";

const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

const applyOffset = (offset) => {
    document.body.style.paddingRight = offset ? `${offset}px` : "";

    const header = document.querySelector(".header");
    if (header) {
        header.style.paddingRight = offset ? `${offset}px` : "";
    }
};

function modalShow() {
    applyOffset(scrollbarWidth);
    document.body.style.overflow = 'hidden';
}
function modalClose(modal) {
    applyOffset(0);
}

export const modalConfig = {
    onShow: modalShow,
    onClose: modalClose,
    awaitOpenAnimation: true,
    awaitCloseAnimation: true,
    disableFocus: true,
    disableScroll: true,
};

export const showModal = (modalID) => MicroModal.show(modalID, modalConfig);