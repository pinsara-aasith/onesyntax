const { default: axios } = require("axios");
const { data } = require("jquery");
const {
    fillTable,
    showError,
    fillComboFromLink,
    showConfirm
} = require("./forms.controller");
const { el, id } = require("./minifier");
const Validator = require("./validator");

require("./minifier");
document.addEventListener("DOMContentLoaded", event => {
    id("btnRefreshUsers").addEventListener("click", refresh);
    id("btnOpenNewUser").addEventListener("click", openNewUser);
    id("btnController").addEventListener("click", createOrUpdate);
    window.validator = new Validator();
    window.validator.addNewValidator(
        id("username"),
        "User Name",
        ["required"],
        "blur"
    );
    window.validator.addNewValidator(
        id("firstname"),
        "First Name",
        ["required"],
        "blur"
    );
    window.validator.addNewValidator(
        id("lastname"),
        "Last Name",
        ["required"],
        "blur"
    );
    window.validator.addNewValidator(
        id("email"),
        "Email",
        ["required"],
        "blur"
    );
    window.validator.addNewValidator(
        id("password"),
        "Password",
        ["required"],
        "blur"
    );
    fillComboFromLink("role", "/api/roles/all", "name", "name");
    refresh();
});

function refresh() {
    id("usersTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/users/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "User Name", value: "username" },
                { name: "First Name", value: "firstname" },
                { name: "Last Name", value: "lastname" },
                { name: "E-mail", value: "email" },
                { name: "Role", value: "role" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "usersTable",
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
        .get("/api/users/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                id("username").value = data.username;
                id("firstname").value = data.firstname;
                id("lastname").value = data.lastname;
                id("email").value = data.email;
                id("role").value = data.role;
                id("btnController").innerHTML = "Edit User";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewUser() {
    id("form").reset();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New User";
    $("#formModal").modal("show");
}

function openRemoveData(data) {
    showConfirm(
        "Removing user may cause many future problems.<br/>Do you really want to delete user...",
        () => {
            axios
                .delete("/api/users/delete/" + data.id)
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
    if (id("btnController").innerHTML == "New User") {
        createUser();
    } else {
        editUser();
    }
}

function createUser() {
    axios
        .post("/api/users/create", {
            username: id("username").value,
            firstname: id("firstname").value,
            lastname: id("lastname").value,
            email: id("email").value,
            password: id("password").value,
            role: id("role").value
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

function editUser() {
    axios
        .put("/api/users/update/" + window.latestData.id, {
            username: id("username").value,
            firstname: id("firstname").value,
            lastname: id("lastname").value,
            email: id("email").value,
            password: id("password").value,
            role: id("role").value
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
