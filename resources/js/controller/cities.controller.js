const { default: axios } = require("axios");
const { data } = require("jquery");
const {
    fillTable,
    showError,
    showConfirm,
    fillComboFromLink
} = require("./forms.controller");
const { el, id } = require("./minifier");
const Validator = require("./validator");

require("./minifier");
document.addEventListener("DOMContentLoaded", event => {
    id("btnRefreshCities").addEventListener("click", refresh);
    id("btnOpenNewCity").addEventListener("click", openNewCity);
    id("btnController").addEventListener("click", createOrUpdate);

    window.validator = new Validator();
    window.validator.addNewValidator(
        id("cityName"),
        "City Name",
        ["required"],
        "finally"
    );
    window.validator.addNewValidator(
        id("state"),
        "State",
        ["required"],
        "finally"
    );
    fillComboFromLink("state", "/api/states/all", "name", "id");
    refresh();
});

function refresh() {
    id("citiesTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/cities/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "City", value: "name" },
                { name: "State", value: "state" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "citiesTable",
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
        .get("/api/cities/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                id("cityName").value = data.name;
                id("state").value = data.state;
                id("btnController").innerHTML = "Edit City";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewCity() {
    id("form").reset();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New City";
    $("#formModal").modal("show");
}

function openRemoveData(data) {
    showConfirm(
        "Removing city may cause many future problems.<br/>Do you really want to delete city...",
        () => {
            axios
                .delete("/api/cities/delete/" + data.id)
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
    if (id("btnController").innerHTML == "New City") {
        createCity();
    } else {
        editCity();
    }
}

function createCity() {
    axios
        .post("/api/cities/create", {
            name: id("cityName").value,
            state: id("state").value
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

function editCity() {
    axios
        .put("/api/cities/update/" + window.latestData.id, {
            name: id("cityName").value,
            state: id("state").value
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
