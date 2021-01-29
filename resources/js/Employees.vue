<template>
  <div>
    <div class="p-3 bg-white border rounded">
      <div class="row">
        <div class="col-md-4">
          <button class="btn btn-md btn-primary" @click="openNewEmployee">
            New Employee <i class="ml-2 fa fa-plus" aria-hidden="true"></i>
          </button>
          <button @click="refresh()" class="btn btn-md btn-success">
            Refresh All
            <i
              class="ml-2 fa fa-refresh"
              @click="refresh"
              aria-hidden="true"
            ></i>
          </button>
        </div>

        <div class="col-md-4">
          <div class="input-group mb-3">
            <select class="form-control" v-model="department2" id="department">
              <option
                v-for="department_dat in departments2"
                :value="department_dat.id"
                :key="department_dat.id"
              >
                {{ department_dat.name }}
              </option>
            </select>
            <div class="input-group-append">
              <button class="btn btn-secondary" @click="filter" type="button">Filter</button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="input-group mb-3">
            <input
              type="text"
              class="form-control"
              placeholder="Name"
              v-model="search2"
            />
            <div class="input-group-append">
              <button class="btn btn-primary" @click="search()" type="button">
                Search
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card p-2 mt-2 bg-white container">
      <table class="table">
        <thead class="thead-primary">
          <tr>
            <th v-for="header in headers" :key="header.value">
              {{ header.name }}
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <span v-if="loading"><b>Loading...</b></span>
          <tr v-for="employee in employees" :key="employee.id">
            <td v-for="header in headers" :key="header.value">
              {{ employee[header.value] }}
            </td>
            <td>
              <button
                @click="openEditEmployee(employee)"
                class="btn btn-sm btn-outline-primary"
              >
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                Edit</button
              ><button
                @click="openRemoveEmployee(employee)"
                class="btn btn-sm ml-2 btn-outline-danger"
              >
                <i class="fa fa-remove" aria-hidden="true"></i> Remove
              </button>
              <button
                @click="viewEmployee(employee)"
                class="btn btn-sm ml-2 btn-outline-success"
              >
                <i aria-hidden="true"></i> View
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div
      class="modal fade"
      id="modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Employee Information</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form">
              <div class="row cols-md-3">
                <div class="col">
                  <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input
                      type="text"
                      class="form-control"
                      :disabled="disabled"
                      v-model="firstname"
                    />
                    <label
                      class="badge text-white bg-danger mt-2 p-1 error-msg"
                      style="display: inline-block"
                      v-if="!firstnameValidate"
                      >First Name is required...</label
                    >
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="middlename">Middle Name</label>
                    <input
                      type="text"
                      class="form-control"
                      :disabled="disabled"
                      v-model="middlename"
                    />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input
                      type="text"
                      class="form-control"
                      :disabled="disabled"
                      v-model="lastname"
                    />
                    <label
                      class="badge text-white bg-danger mt-2 p-1 error-msg"
                      v-if="!lastnameValidate"
                      style="display: inline-block"
                      >Last Name is required...
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <input
                  type="text"
                  class="form-control"
                  :disabled="disabled"
                  v-model="address"
                />
              </div>
              <div class="row cols-md-2">
                <div class="col">
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Birth Day</label>
                    <input
                      type="date"
                      class="form-control"
                      :disabled="disabled"
                      v-model="birthdate"
                    />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="hireddate">Hired Date</label>
                    <input
                      type="date"
                      class="form-control"
                      :disabled="disabled"
                      v-model="datehired"
                    />
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="department">Department</label>
                <select
                  class="form-control"
                  :disabled="disabled"
                  v-model="department"
                  id="department"
                >
                  <option
                    v-for="department_dat in departments"
                    :value="department_dat.id"
                    :key="department_dat.id"
                  >
                    {{ department_dat.name }}
                  </option>
                </select>
                <label
                  class="badge text-white bg-danger mt-2 p-1 error-msg"
                  v-if="!departmentValidate"
                  style="display: inline-block"
                  >Department is required...
                </label>
              </div>

              <div class="row cols-md-2">
                <div class="col">
                  <label for="country">Country</label>
                  <select
                    v-model="country"
                    :disabled="disabled"
                    class="form-control"
                    @change="countryChange"
                  >
                    <option
                      v-for="country_dat in countries"
                      :value="country_dat.id"
                      :key="country_dat.id"
                    >
                      {{ country_dat.name }}
                    </option>
                  </select>
                  <label
                    class="badge text-white bg-danger mt-2 p-1 error-msg"
                    v-if="!countryValidate"
                    style="display: inline-block"
                    >Country is required...
                  </label>
                </div>
                <div class="col">
                  <label for="exampleFormControlSelect2">State</label>
                  <select
                    class="form-control"
                    :disabled="disabled"
                    @change="stateChange"
                    v-model="state"
                  >
                    <option
                      v-for="state_dat in states"
                      :value="state_dat.id"
                      :key="state_dat.id"
                    >
                      {{ state_dat.name }}
                    </option>
                  </select>
                  <label
                    class="badge text-white bg-danger mt-2 p-1 error-msg"
                    v-if="!stateValidate"
                    style="display: inline-block"
                    >State is required...
                  </label>
                </div>
              </div>

              <div class="row cols-md-2">
                <div class="col">
                  <label for="country">City</label>
                  <select
                    v-model="city"
                    :disabled="disabled"
                    class="form-control"
                    id="city"
                  >
                    <option
                      v-for="city_dat in cities"
                      :value="city_dat.id"
                      :key="city_dat.id"
                    >
                      {{ city_dat.name }}
                    </option>
                  </select>
                  <label
                    class="badge text-white bg-danger mt-2 p-1 error-msg"
                    v-if="!cityValidate"
                    style="display: inline-block"
                    >City is required...
                  </label>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="exampleFormControlSelect2">Zip</label>
                    <input
                      type="text"
                      :disabled="disabled"
                      v-model="zip"
                      class="form-control"
                      id="exampleFormControlInput1"
                    />
                    <label
                      class="badge text-white bg-danger mt-2 p-1 error-msg"
                      v-if="!zipValidate"
                      style="display: inline-block"
                      >Zip is required...
                    </label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              @click="createOrUpdate()"
              class="btn btn-primary"
            >
              {{ btnText }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TableAction from "./components/TableAction.vue";
const { default: axios } = require("axios");
const {
  fillTable,
  showError,
  showConfirm,
} = require("./controller/forms.controller");
const { el, id } = require("./controller/minifier");
const Validator = require("./controller/validator");
export default {
  components: {
    TableAction,
  },
  data: () => ({
    headers: [
      { name: "ID", value: "id" },
      { name: "First Name", value: "firstname" },
      { name: "Last Name", value: "lastname" },
      { name: "City", value: "city" },
      { name: "Address", value: "address" },
      { name: "Department", value: "department" },
    ],
    employees: [],
    loading: false,

    firstname: null,
    lastname: null,
    middlename: null,
    address: null,
    birthdate: null,
    datehired: null,
    department: null,
    country: null,
    state: null,
    city: null,
    zip: null,
    disabled: true,
    btnText: "New Employee",
    firstnameValidate: true,
    lastnameValidate: true,
    departmentValidate: true,
    countryValidate: true,
    stateValidate: true,
    cityValidate: true,
    zipValidate: true,
    addressValidate: true,

    countries: [],
    departments: [],
    departments2: [],
    department2: null,
    search2: null,
    states: [],
    cities: [],

    latest: null,

    mustValids: [
      "firstname",
      "lastname",
      "address",
      "department",
      "country",
      "state",
      "city",
      "zip",
    ],
  }),
  methods: {
    refresh() {
      axios
        .get("/api/employees/all")
        .then((res) => {
          if (res.data.status === "success") {
            this.employees = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
    },
    countryChange() {
      axios
        .get("/api/states/allByCountry/" + this.country)
        .then((res) => {
          if (res.data.status === "success") {
            this.states = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
    },
    stateChange() {
      axios
        .get("/api/cities/allByState/" + this.state)
        .then((res) => {
          if (res.data.status === "success") {
            this.cities = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
    },
    validateEmployee() {
      let validated = true;
      for (let mustValid of this.mustValids) {
        if (this[mustValid] == "" || this[mustValid] == null) {
          this[mustValid + "Validate"] = false;
          validated = false;
        } else {
          this[mustValid + "Validate"] = true;
        }
      }
      return validated;
    },
    clearAll() {
      for (let mustValid of this.mustValids) {
        this[mustValid + "Validate"] = true;
      }

      this.firstname = null;
      this.lastname = null;
      this.lastname = null;
      this.address = null;
      this.zip = null;
      this.datehired = null;
      this.birthdate = null;
      this.department = null;
      this.state = null;
      this.country = null;
      this.city = null;
    },
    refreshFields() {
      axios
        .get("/api/countries/all")
        .then((res) => {
          if (res.data.status === "success") {
            this.countries = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });

      axios
        .get("/api/states/all")
        .then((res) => {
          if (res.data.status === "success") {
            this.states = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });

      axios
        .get("/api/cities/all")
        .then((res) => {
          if (res.data.status === "success") {
            this.cities = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
      this.loading = true;
      axios
        .get("/api/departments/all")
        .then((res) => {
          if (res.data.status === "success") {
            this.departments = res.data.data;
            this.loading = false;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
    },
    openNewEmployee() {
      this.refreshFields();
      this.btnText = "New Employee";
      this.clearAll();
      this.disabled = false;
      id("form").reset();
      $("#modal").modal("show");
    },
    openEditEmployee(latest) {
      this.refreshFields();
      this.btnText = "Edit Employee";
      this.clearAll();
      this.latest = latest;
      this.disabled = false;
      id("form").reset();
      axios
        .get("/api/employees/read/" + latest.id)
        .then((res) => {
          if (res.data.status === "success") {
            let data = res.data.data;
            this.firstname = data.firstname;
            this.lastname = data.lastname;
            this.address = data.address;
            this.zip = data.zip;
            this.datehired = data.datehired;
            this.birthdate = data.birthdate;
            this.department = data.department;
            this.country = data.country;
            this.state = data.state;
            this.city = data.city;
            this.middlename = data.middlename;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
      $("#modal").modal("show");
    },
    openRemoveEmployee(element) {
      showConfirm(
        "Removing employee may cause many future problems.<br/>Do you really want to delete this employee...",
        () => {
          axios
            .delete("/api/employees/delete/" + element.id)
            .then((res) => {
              if (res.data.status === "success") {
                this.refresh();
              } else showError(res.data.message);
            })
            .catch((error) => {
              showError(error);
            });
        }
      );
    },
    viewEmployee(employee) {
      this.refreshFields();
      this.btnText = "Edit Employee";
      this.clearAll();
      this.disabled = true;
      id("form").reset();
      axios
        .get("/api/employees/read/" + employee.id)
        .then((res) => {
          if (res.data.status === "success") {
            let data = res.data.data;
            this.firstname = data.firstname;
            this.lastname = data.lastname;
            this.address = data.address;
            this.zip = data.zip;
            this.datehired = data.datehired;
            this.birthdate = data.birthdate;
            this.department = data.department;
            this.country = data.country;
            this.state = data.state;
            this.city = data.city;
            this.middlename = data.middlename;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
      $("#modal").modal("show");
    },
    createOrUpdate() {
      if (!this.validateEmployee()) {
        return;
      }
      if (this.btnText == "New Employee") {
        this.createEmployee();
      } else {
        this.editEmployee();
      }
    },
    createEmployee() {
      axios
        .post("/api/employees/create", {
          firstname: this.firstname,
          lastname: this.lastname,
          middlename: this.middlename,
          address: this.address,
          zip: this.zip,
          datehired: this.datehired,
          birthdate: this.birthdate,
          department: this.department,
          country: this.country,
          state: this.state,
          city: this.city,
        })
        .then((res) => {
          if (res.data.status === "success") {
            this.refresh();
          } else showError(res.data.message);
        })
        .catch((error) => {
          showError(error);
        });
      $("#modal").modal("hide");
    },
    filter() {
      axios
        .get("/api/employees/allByDepartment/" + this.department2)
        .then((res) => {
          if (res.data.status === "success") {
            this.employees = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
    },
    search() {
      axios
        .get("/api/employees/allBySearchName/", {
          params: { search: this.search2 },
        })
        .then((res) => {
          if (res.data.status === "success") {
            this.employees = res.data.data;
          } else {
            showError();
          }
        })
        .catch((error) => {
          showError(error);
        });
    },
    editEmployee() {
      axios
        .put("/api/employees/update/" + this.latest.id, {
          firstname: this.firstname,
          lastname: this.lastname,
          middlename: this.middlename,
          address: this.address,
          zip: this.zip,
          datehired: this.datehired,
          birthdate: this.birthdate,
          department: this.department,
          country: this.country,
          state: this.state,
          city: this.city,
        })
        .then((res) => {
          if (res.data.status === "success") {
            this.refresh();
          } else showError(res.data.message);
        })
        .catch((error) => {
          showError(error);
        });
      $("#modal").modal("hide");
    },
  },
  created() {
    this.refresh();
    axios
      .get("/api/departments/all")
      .then((res) => {
        if (res.data.status === "success") {
          this.departments2 = res.data.data;
          this.loading = false;
        } else {
          showError();
        }
      })
      .catch((error) => {
        showError(error);
      });
  },
};
</script>
<style>
.modal {
  background: #00000099;
}
.modal-backdrop {
  display: block;
  z-index: 90;
}
.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>