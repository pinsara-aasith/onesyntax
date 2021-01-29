const { default: axios } = require("axios");
const { data } = require("jquery");
const { fillTable, showError } = require("./forms.controller");
const { el, id } = require("./minifier");
const Validator = require("./validator");

require("./minifier");
document.addEventListener("DOMContentLoaded", event => {
    id("btnRefreshPermissions").addEventListener("click", refresh);
    id("btnOpenNewPermission").addEventListener("click", openNewPermission);
    id("btnController").addEventListener("click", createOrUpdate);

    window.validator = new Validator();
    window.validator.addNewValidator(
        id("permissionName"),
        "Permission Name",
        ["required"],
        "finally"
    );
    refresh();
});

function refresh() {
    id("permissionsTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/permissions/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "Permission", value: "name" },
                { name: "Created At", value: "created_at" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "permissionsTable",
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
        .get("/api/permissions/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                id("permissionName").value = data.name;
                id("btnController").innerHTML = "Edit Permission";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewPermission() {
    id("form").reset();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New Permission";
    $("#formModal").modal("show");
}

function openRemoveData() {
    showError(
        "Removing permission may cause many future problems.<br/>So you are not allowed..."
    );
}

function createOrUpdate() {
    if (!window.validator.validateAll()) {
        return;
    }
    if (id("btnController").innerHTML == "New Permission") {
        createPermission();
    } else {
        editPermission();
    }
}

function createPermission() {
    axios
        .post("/api/permissions/create", { name: id("permissionName").value })
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

function editPermission() {
    axios
        .put("/api/permissions/update/" + window.latestData.id, { name: id("permissionName").value })
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
