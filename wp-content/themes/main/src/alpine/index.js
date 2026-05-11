import Alpine from "alpinejs";
import mask from '@alpinejs/mask';
import collapse from '@alpinejs/collapse'

Alpine.plugin(mask);
Alpine.plugin(collapse);

import PhoneInputMask from "./components/PhoneInputMask";
Alpine.data("PhoneInputMask", PhoneInputMask);

// import Modal from "./components/Modal";
// Alpine.data("Modal", Modal)

import OpenModal from "./components/OpenModal/OpenModal"
Alpine.data("OpenModal", OpenModal)

import FancyboxGallery from "./components/FancyboxGallery";
Alpine.data("FancyboxGallery", FancyboxGallery)

import Accordion from "./components/Accordion";
Alpine.data("Accordion", Accordion)

import Contacts from "./components/Contacts";
Alpine.data("Contacts", Contacts)

Alpine.store('mainMenu', {
    isOpen: false
})

Alpine.store('callbackModal', {
    isOpen: false
})

Alpine.start();
