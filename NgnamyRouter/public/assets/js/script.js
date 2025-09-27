import { ValidationForm } from "./src/ValidationForm.js";

const validationRules = {
    name: (value) => /^[a-zA-Z\s]+$/.test(value) && value.length >= 3,
    email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
    password: (value) => value.length >= 8,
    message: (value) => value.length >= 10,
    telCameroun: (value) => /^(\+237\s|0)\d{9}$/.test(value),
};

const form = document.querySelector('.contact-form');
const contactForm = new ValidationForm(form);

contactForm.addField('name', 'name', {rule: validationRules.name, errorMessage: 'Nom invalide : doit contenir uniquement des lettres et faire au moins 3 caractères.'});

contactForm.addField('email', 'email', {rule: validationRules.email, errorMessage: 'Email invalide : doit respecter un format valide (ex: contact@domaine.com).'});

contactForm.addField('tel', 'tel', {rule: validationRules.telCameroun, errorMessage: 'Numéro de téléphone camerounais invalide (+237 671234567)'});

contactForm.addField('message', 'message', {rule: validationRules.message, errorMessage: 'Message invalide : doit contenir au moins 10 caractères.'});