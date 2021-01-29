const { default: axios } = require("axios");
const { data } = require("jquery");
const { fillTable, showError, showConfirm } = require("./forms.controller");
const { el, id } = require("./minifier");
const Validator = require("./validator");

require("./minifier");
document.addEventListener("DOMContentLoaded", event => {
    id("btnRefreshCountries").addEventListener("click", refresh);
    id("btnOpenNewCountry").addEventListener("click", openNewCountry);
    id("btnController").addEventListener("click", createOrUpdate);

    window.validator = new Validator();
    window.validator.addNewValidator(
        id("countryName"),
        "Country Name",
        ["required"],
        "finally"
    );
    window.validator.addNewValidator(
        id("countryCode"),
        "Country Code",
        ["required"],
        "finally"
    );
    refresh();
});

function refresh() {
    id("countriesTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/countries/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "Country", value: "name" },
                { name: "Code", value: "country_code" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "countriesTable",
                    headers,
                    res.data.data,
                    true,
                    openEditData,
                    openRemoveData
                );
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openEditData(data) {
    id("form").reset();
    window.validator.clearValidations();
    window.latestData = data;
    axios
        .get("/api/countries/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                id("countryName").value = data.name;
                id("countryCode").value = data.country_code;
                id("btnController").innerHTML = "Edit Country";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewCountry() {
    id("form").reset();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New Country";
    $("#formModal").modal("show");
}

function openRemoveData(data) {
    showConfirm(
        "Removing country may cause many future problems.<br/>Do you really want to delete country...",
        () => {
            axios
                .delete("/api/countries/delete/" + data.id)
                .then(res => {
                    if (res.data.status === "success") {
                        refresh();
                    } else showError(res.data.message);
                })
                .catch(error => {
                    showError(error);
                });
        }
    );
}

function createOrUpdate() {
    if (!window.validator.validateAll()) {
        return;
    }
    if (id("btnController").innerHTML == "New Country") {
        createCountry();
    } else {
        editCountry();
    }
}

function createCountry() {
    axios
        .post("/api/countries/create", {
            name: id("countryName").value,
            country_code: id("countryCode").value
        })
        .then(res => {
            if (res.data.status === "success") {
                refresh();
            } else showError(res.data.message);
        })
        .catch(error => {
            showError(error);
        });
    $("#formModal").modal("hide");
}

function editCountry() {
    axios
        .put("/api/countries/update/" + window.latestData.id, {
            name: id("countryName").value,
            country_code: id("countryCode").value
        })
        .then(res => {
            if (res.data.status === "success") {
                refresh();
            } else showError(res.data.message);
        })
        .catch(error => {
            showError(error);
        });
    $("#formModal").modal("hide");
}
