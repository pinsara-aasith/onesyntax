import Vue from "vue";
import Employees from "./Employees.vue";
require("./bootstrap");
let location = window.location.toString();
let origin = window.location.origin;
if (location.startsWith(origin + "/permissions"))
    require("./controller/permissions.controller");
else if (location.startsWith(origin + "/roles"))
    require("./controller/roles.controller");
else if (location.startsWith(origin + "/users"))
    require("./controller/users.controller");
else if (location.startsWith(origin + "/countries"))
    require("./controller/countries.controller");
else if (location.startsWith(origin + "/states"))
    require("./controller/states.controller");
else if (location.startsWith(origin + "/cities"))
    require("./controller/cities.controller");
else if (location.startsWith(origin + "/departments"))
    require("./controller/departments.controller");
else if (location.startsWith(origin + "/employees")) {
    

    Vue.config.productionTip = false;
    window.onload = function() {
        const vueElement = new Vue({
            render: h => h(Employees)
        }).$mount("#app");
    };
}
