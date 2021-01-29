const { default: axios } = require("axios");
const { data } = require("jquery");
const { fillTable, showError, showConfirm } = require("./forms.controller");
const { el, id } = require("./minifier");
const Validator = require("./validator");

require("./minifier");
document.addEventListener("DOMContentLoaded", event => {
    id("btnRefreshDepartments").addEventListener("click", refresh);
    id("btnOpenNewDepartment").addEventListener("click", openNewDepartment);
    id("btnController").addEventListener("click", createOrUpdate);

    window.validator = new Validator();
    window.validator.addNewValidator(
        id("departmentName"),
        "Department Name",
        ["required"],
        "finally"
    );
    refresh();
});

function refresh() {
    id("departmentsTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/departments/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "Department", value: "name" },
                { name: "Created At", value: "created_at" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "departmentsTable",
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


function openRemoveData(data) {
    showConfirm(
        "Removing department may cause many future problems.<br/>Do you really want to delete this department...",
        () => {
            axios
                .delete("/api/departments/delete/" + data.id)
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

function openEditData(data) {
    id("form").reset();
    window.validator.clearValidations();
    window.latestData = data;
    axios
        .get("/api/departments/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                id("departmentName").value = data.name;
                id("btnController").innerHTML = "Edit Department";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewDepartment() {
    id("form").reset();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New Department";
    $("#formModal").modal("show");
}

function createOrUpdate() {
    if (!window.validator.validateAll()) {
        return;
    }
    if (id("btnController").innerHTML == "New Department") {
        createDepartment();
    } else {
        editDepartment();
    }
}

function createDepartment() {
    axios
        .post("/api/departments/create", { name: id("departmentName").value })
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

function editDepartment() {
    axios
        .put("/api/departments/update/" + window.latestData.id, { name: id("departmentName").value })
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
