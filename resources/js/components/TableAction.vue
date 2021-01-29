<template>
  <td>
    <button class="btn btn-sm btn-outline-primary">
      <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button
    ><button class="btn btn-sm ml-2 btn-outline-danger">
      <i class="fa fa-remove" aria-hidden="true"></i> Remove
    </button>
    <button class="btn btn-sm ml-2 btn-outline-success">
      <i  aria-hidden="true"></i> View
    </button>
  </td>
  
</template>

<script>
export default {
  name: "TableAction",
  data() {
    return {
      data: [],
    };
  },
};
</script>