import axios from "axios";
import { el, id } from "./minifier";

function resetTable() {}

function fillComboFromLink(comboId, link, itemName, itemValue) {
    axios.get(link).then(res => {
        let combo = id(comboId);
        combo.innerHTML = "";
        let data = res.data.data;
        for (let item of data) {
            let option = el("option");
            option.value = item[itemValue];
            option.innerHTML = item[itemName];
            combo.appendChild(option);
        }
    });
}

function fillCombo(comboId, data, itemName, itemValue) {}

function fillTable(
    tableId,
    headers,
    data,
    wantActions = true,
    editController = null,
    deleteController = null,
    wantView = false,
    viewController = null
) {
    id(tableId).innerHTML = "";
    let thead = el("thead");
    let tr = el("tr");
    for (let header of headers) {
        let td = el("th");
        td.innerHTML = header.name;
        tr.appendChild(td);
    }
    if (wantActions) {
        let td = el("th");
        td.innerHTML = "Actions";
        tr.appendChild(td);
    }
    thead.appendChild(tr);
    id(tableId).appendChild(thead);

    let tbody = el("tbody");
    for (let dataItem of data) {
        let tr = el("tr");
        for (let header of headers) {
            let td = el("td");
            if (dataItem[header.value] !== undefined) {
                td.innerHTML = dataItem[header.value];
            }
            tr.appendChild(td);
        }
        if (wantActions) {
            let td = el("td");
            let btnEdit = el("button");
            btnEdit.classList.add("btn");
            btnEdit.classList.add("btn-sm");
            btnEdit.classList.add("btn-outline-primary");
            btnEdit.innerHTML =
                '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button>';
            btnEdit.addEventListener("click", () => {
                editController && editController(dataItem);
            });
            td.appendChild(btnEdit);

            let btnRemove = el("button");
            btnRemove.classList.add("btn");
            btnRemove.classList.add("btn-sm");
            btnRemove.classList.add("ml-2");
            btnRemove.classList.add("btn-outline-danger");
            btnRemove.innerHTML =
                '<i class="fa fa-remove" aria-hidden="true"></i> Remove</button>';
            btnRemove.addEventListener("click", () => {
                deleteController && deleteController(dataItem);
            });
            td.appendChild(btnRemove);
            if (wantView) {
                let btnView = el("button");
                btnView.classList.add("btn");
                btnView.classList.add("btn-sm");
                btnView.classList.add("ml-2");
                btnView.classList.add("btn-outline-success");
                btnView.innerHTML =
                    '<i class="fa fa-edit" aria-hidden="true"></i> View</button>';
                btnView.addEventListener("click", () => {
                    viewController && viewController(dataItem);
                });
                td.appendChild(btnView);
            }
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }

    id(tableId).appendChild(tbody);
}

function showConfirm(msg, afterConfirm) {
    id("confirmText").innerHTML = msg;
    $("#confirmModal").modal("show");
    let el = id("confirmButton");
    let elClone = el.cloneNode(true);
    el.parentNode.replaceChild(elClone, el);
    id("confirmButton").addEventListener("click", () => {
        afterConfirm();
        $("#confirmModal").modal("hide");
    });

    let el2 = id("cancelButton");
    let el2Clone = el2.cloneNode(true);
    el2.parentNode.replaceChild(el2Clone, el2);
    id("cancelButton").addEventListener("click", () => {
        $("#confirmModal").modal("hide");
    });
}

function showError(error = null) {
    if (
        error &&
        error.response &&
        error.response.data &&
        error.response.data.errors
    ) {
        let ul = el("ul");
        ul.classList.add("list-group");
        for (let err in error.response.data.errors) {
            let li = el("li");
            li.classList.add("list-group-item", "text-white", "bg-danger");
            li.innerHTML = error.response.data.errors[err][0].toLowerCase();
            ul.appendChild(li);
        }
        id("errorText").innerHTML = "";
        id("errorText").appendChild(ul);
        $("#errorModal").modal("show");
        return;
    }
    if (error == null)
        id("errorText").innerHTML = "Something went wrong. Please try again...";
    else id("errorText").innerHTML = error;
    $("#errorModal").modal("show");
}

export { fillTable, fillCombo, fillComboFromLink, showError, showConfirm };
