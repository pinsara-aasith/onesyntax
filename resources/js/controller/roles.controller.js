const { default: axios } = require("axios");
const { data } = require("jquery");
const { fillTable, showError } = require("./forms.controller");
const { el, id } = require("./minifier");
const Validator = require("./validator");

require("./minifier");
document.addEventListener("DOMContentLoaded", event => {
    id("btnRefreshRoles").addEventListener("click", refresh);
    id("btnOpenNewRole").addEventListener("click", openNewRole);
    id("btnController").addEventListener("click", createOrUpdate);

    window.validator = new Validator();
    window.validator.addNewValidator(
        id("roleName"),
        "Role Name",
        ["required"],
        "finally"
    );
    window.allPermissions = [];
    window.leftPermissions = id("leftPermissions");
    window.rightPermissions = id("rightPermissions");
    refresh();
    refreshPermissions();
});

function sendLeft(permission, li) {
    const index = window.permissions.indexOf(permission);
    if (index > -1) {
        window.permissions.splice(index, 1);
    }

    rightPermissions.removeChild(li);
    let li2 = el("li");
    li2.classList.add(
        "list-group-item",
        "d-flex",
        "p-1",
        "flex-row",
        "align-items-center"
    );
    let span = el("span");
    span.classList.add("p-1", "text-white", "bg-secondary", "rounded");
    span.style.fontSize = "12px";
    span.innerHTML = permission.name;
    let button = el("button");
    button.classList.add(
        "btn",
        "btn-md",
        "font-weight-bold",
        "btn-primary",
        "ml-auto"
    );
    button.innerHTML = '<i class="fa fa-hand-o-right"></i>';
    button.addEventListener("click", () => sendRight(permission, li2));
    li2.appendChild(span);
    li2.appendChild(button);
    leftPermissions.appendChild(li2);
}

function sendRight(permission, li) {
    window.permissions.push(permission);
    leftPermissions.removeChild(li);
    let li2 = el("li");
    li2.classList.add(
        "list-group-item",
        "d-flex",
        "p-1",
        "flex-row",
        "align-items-center"
    );
    let span = el("span");
    span.classList.add("p-1", "text-white", "bg-secondary", "rounded");
    span.style.fontSize = "12px";
    span.innerHTML = permission.name;
    let button = el("button");
    button.classList.add(
        "btn",
        "btn-md",
        "font-weight-bold",
        "btn-warning",
        "ml-auto"
    );
    button.innerHTML = '<i class="fa fa-hand-o-left"></i>';
    button.addEventListener("click", () => sendLeft(permission, li2));
    li2.appendChild(span);
    li2.appendChild(button);
    rightPermissions.appendChild(li2);
}

function createPermissions(left, right) {
    leftPermissions.innerHTML = "";
    rightPermissions.innerHTML = "";
    if (left)
        for (let permission of left) {
            let li = el("li");
            li.classList.add(
                "list-group-item",
                "d-flex",
                "p-1",
                "flex-row",
                "align-items-center"
            );
            let span = el("span");
            span.classList.add("p-1", "text-white", "bg-secondary", "rounded");
            span.style.fontSize = "12px";
            span.innerHTML = permission.name;
            let button = el("button");
            button.classList.add(
                "btn",
                "btn-md",
                "font-weight-bold",
                "btn-primary",
                "ml-auto"
            );
            button.addEventListener("click", () => sendRight(permission, li));
            button.innerHTML = '<i class="fa fa-hand-o-right"></i>';

            li.appendChild(span);
            li.appendChild(button);
            leftPermissions.appendChild(li);
        }

    if (right)
        for (let permission of right) {
            let li = el("li");
            li.classList.add(
                "list-group-item",
                "d-flex",
                "p-1",
                "flex-row",
                "align-items-center"
            );
            let span = el("span");
            span.classList.add("p-1", "text-white", "bg-secondary", "rounded");
            span.style.fontSize = "12px";
            span.innerHTML = permission.name;
            let button = el("button");
            button.classList.add(
                "btn",
                "btn-md",
                "font-weight-bold",
                "btn-danger",
                "ml-auto"
            );
            button.addEventListener("click", () => sendLeft(permission, li));
            button.innerHTML = '<i class="fa fa-hand-o-left"></i>';

            li.appendChild(span);
            li.appendChild(button);
            rightPermissions.appendChild(li);
        }
}

function refreshPermissions() {
    window.permissions = [];
    axios
        .get("/api/permissions/all")
        .then(res => {
            if (res.data.status === "success") {
                window.allPermissions = res.data.data;
                createPermissions(res.data.data);
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function refresh() {
    id("rolesTable").innerHTML = "<b>Loading.....</b>";
    axios
        .get("/api/roles/all")
        .then(res => {
            let headers = [
                { name: "No", value: "id" },
                { name: "Role", value: "name" },
                { name: "Created At", value: "created_at" }
            ];
            if (res.data.status === "success") {
                fillTable(
                    "rolesTable",
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
        .get("/api/roles/read/" + data.id)
        .then(res => {
            if (res.data.status === "success") {
                let data = res.data.data;
                console.log(data);
                let permissions_from_data = data.permissions;
                window.permissions = permissions_from_data;
                let left = [];
                for (let per of window.allPermissions) {
                    let found = false;
                    for (let permission_from_data of permissions_from_data) {
                        if (per.id == permission_from_data.id) {
                            found = true;
                            break;
                        }
                    }
                    if (!found) left.push(per);
                }
                createPermissions(left, permissions_from_data);
                id("roleName").value = data.name;
                id("btnController").innerHTML = "Edit Role";
                $("#formModal").modal("show");
            } else {
                showError();
            }
        })
        .catch(error => {
            showError(error);
        });
}

function openNewRole() {
    id("form").reset();
    refreshPermissions();
    window.validator.clearValidations();
    id("btnController").innerHTML = "New Role";
    $("#formModal").modal("show");
}

function openRemoveData() {
    showError(
        "Removing role may cause many future problems.<br/>So you are not allowed..."
    );
}

function createOrUpdate() {
    if (!window.validator.validateAll()) {
        return;
    }
    if (id("btnController").innerHTML == "New Role") {
        createRole();
    } else {
        editRole();
    }
}

function createRole() {
    axios
        .post("/api/roles/create", {
            name: id("roleName").value,
            permissions: window.permissions
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

function editRole() {
    axios
        .put("/api/roles/update/" + window.latestData.id, {
            name: id("roleName").value,
            permissions: window.permissions
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
