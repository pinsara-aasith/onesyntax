const { isBuffer } = require("lodash");
const { el } = require("./minifier");

class Validator {
    constructor(formId) {
        this.validateArray = [];
        this.formId = formId;
    }

    clearValidations() {
        for (validate of this.validateArray) {
            this.removeError(validate.element);
        }
    }

    addNewValidator(element, name, validators = ["required"], on = "change") {
        this.validateArray.push({
            element: element,
            name: name,
            validators: validators,
            on: on
        });
        this.setValidate(element, name, validators, on);
    }

    validateAll() {
        let validated = true;
        for (validate of this.validateArray) {
            if (
                !this.validate(
                    validate.element,
                    validate.name,
                    validate.validators
                )
            ) {
                validated = false;
            }
        }
        return validated;
    }

    removeError(element) {
        let previouserrors = element.parentElement.querySelectorAll(
            ".error-msg"
        );
        for (let previouserror of previouserrors) {
            previouserror.remove();
        }
    }

    setError(element, msg) {
        let lbl = el("label");
        lbl.classList.add(
            "badge",
            "text-white",
            "bg-danger",
            "mt-2",
            "p-1",
            "error-msg"
        );
        lbl.innerHTML = msg;
        element.parentElement.appendChild(lbl);
        $(lbl).fadeIn();
    }

    validate(element, name, validators) {
        let value = element.value;
        let validated = true;
        let msg = "";
        this.removeError(element);
        for (let validator of validators) {
            if (validator == "required") {
                if (value == "" || value == null) {
                    validated = false;
                    msg = name + " is required...";
                    this.setError(element, msg);
                    return validated;
                }
            }
        }
        return validated;
    }

    setValidate(element, name, validators, on) {
        if (on == "finally") return;
        element.addEventListener(on, () => {
            this.validate(element, name, validators);
        });
    }
}

module.exports = Validator;
