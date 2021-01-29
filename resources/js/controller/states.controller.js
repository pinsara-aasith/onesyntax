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
    id("btnRefreshStates").addEventListener("click", refresh);
    id("btnOpenNewState").addEventListener("click", openNewState);
    id("btnController").addEventListener("click", createOrUpdate);

    window.validator = new Validator();
    window.validator.addNewValidator(
        id("stateName"),
        "State Name",
        ["required"],
        "finally"
    );
    window.validator.addNewValidator(
        id("country"),
        "Country",
        ["required"],
        "finally"
    );
    fillComboFromLink("country", "/api/countries/all", "name", "id");
    refresh();
});

function refresh() {
    id("statesTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/states/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "State", value: "name" },
                { name: "Country", value: "country" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "statesTable",
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
        .get("/api/states/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                id("stateName").value = data.name;
                id("country").value = data.country;
                id("btnController").innerHTML = "Edit State";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewState() {
    id("form").reset();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New State";
    $("#formModal").modal("show");
}

function openRemoveData(data) {
    showConfirm(
        "Removing state may cause many future problems.<br/>Do you really want to delete state...",
        () => {
            axios
                .delete("/api/states/delete/" + data.id)
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
    if (id("btnController").innerHTML == "New State") {
        createState();
    } else {
        editState();
    }
}

function createState() {
    axios
        .post("/api/states/create", {
            name: id("stateName").value,
            country: id("country").value
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

function editState() {
    axios
        .put("/api/states/update/" + window.latestData.id, {
            name: id("stateName").value,
            country: id("country").value
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
