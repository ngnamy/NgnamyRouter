/**
 * Gère la validation côté client d'un formulaire HTML.
 * Elle centralise la logique de validation et l'affichage des messages d'erreur.
 */
export class ValidationForm {

    form;

    /**
     * @param {HTMLFormElement} form L'élément du formulaire HTML à valider.
     */
    constructor(form) {
        this.form = form;
        this.fieldsToSuitcase = {};
        this.errorMessages = {};

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.#verification(e.currentTarget);
        })
    }
    /**
     * Enregistre un champ du formulaire pour la validation.
     * 
     * @param {string} fieldName La valeur de l'attribut 'name' du champ à valider.
     * @param {string} errorAttribute La valeur de l'attribut 'data-error' de l'élément où afficher le message d'erreur.
     * @param {object} validationOptions Un objet contenant la règle de validation et le message d'erreur.
     * @param {Function} validationOptions.rule Une fonction qui prend la valeur du champ en entrée et retourne `true` si elle est valide, sinon `false`.
     * @param {string} validationOptions.errorMessage Le message à afficher si la validation échoue.
     * @returns {void}
     */
    addField(fieldName, errorAttribute, validationOptions) {
        const fieldElement = this.form.querySelector(`[name="${fieldName}"]`);
        const errorElement = this.form.querySelector(`[data-error="${errorAttribute}"]`);

        // Si le champ ou l'élément d'erreur n'est pas trouvé, on arrête pour éviter des erreurs.
        if (!fieldElement || !errorElement) {
            console.error(`Impossible de trouver le champ '${fieldName}' ou l'élément d'erreur '[data-error="${errorAttribute}"]'`);
            return;
        }

        this.fieldsToSuitcase[fieldName] = {
            element: fieldElement,
            errorElement: errorElement,
            rule: validationOptions.rule, // On stocke la fonction de validation, pas son résultat.
            errorMessage: validationOptions.errorMessage,
        };
    }

    /**
     * 
     * Verifies the form and displays error messages if any.
     *
     * @param {HTMLFormElement} form 
     */
    #verification(form) {
        let isValid = true;

        // Select all elements with the class 'is-invalid'.
        const element = form.querySelectorAll('.is-invalid');
        // Select all elements with the class 'error-message'.
        const elementError = form.querySelectorAll('.error-message');

        // If there are any error messages.
        if (elementError !== null) {
            // Loop through each error message.
            elementError.forEach((item) => {
                // Clear the error message.
                item.innerText = '';
                // Hide the error message.
                item.style.display = 'none';
            })
        }

        // If there are any invalid elements.
        if (element !== null) {
            // Loop through each invalid element.
            element.forEach((item) => {
                // Remove the 'is-invalid' class.
                item.classList.remove('is-invalid');
            })
        }

        // Loop through each field to validate.
        Object.values(this.fieldsToSuitcase).forEach((item) => {
            // If the field is not valid.
            if (!item.rule(item.element.value)) {
                // Set isValid to false.
                isValid = false;
                // Add the error message to the errorMessages object.
                this.errorMessages[item.element.name] = item.errorMessage;
                // Add the 'is-invalid' class to the element.
                item.element.classList.add('is-invalid');
                // Display the error message.
                item.errorElement.style.display = 'block';
                // Set the error message text.
                item.errorElement.innerText = item.errorMessage;
            }
        })

        // If the form is valid.
        if (isValid) {
            console.log("Soumission du formulaire avec succes");
            // Reset the form.
            form.reset();
            // Submit the form.
            form.submit();
        } else {
            // Log the error messages.
            console.log(this.errorMessages);
        }
        
    }
}