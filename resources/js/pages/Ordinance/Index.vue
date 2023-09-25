<script setup>
import Layout from "@/layouts/default.vue";

import { useCrud } from "@/Composables/Crud.js";
import { router } from "@inertiajs/vue3";
import axios from 'axios';
const routeName = "ordinances";



const  printRoute = 'ordinances-view';


const formObject = {};
let {
  isLoadingComponents,
  paginatedData,
  form,
  createPromise,
  updatePromise,
  deletePromise,
  handleCreate,
  serverQuery,
  handleServerQuery,
  handleEdit,
  formState,
  print,
} = useCrud(formObject, routeName, printRoute);
</script>

<script>
import Editor from "@tinymce/tinymce-vue";
export default {
  components: {
    Editor,
  },
  name: "Staff",
  layout: (h, page) => h(Layout, [page]),
  data() {
    return {
      content: "",
      editorSettings: {
        plugins: [
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste code help wordcount",
        ],
        toolbar:
          "undo redo | formatselect | " +
          "bold italic backcolor | alignleft aligncenter " +
          "alignright alignjustify | bullist numlist outdent indent | " +
          "removeformat | help",
      },
    };
  },
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="container mx-auto">
      <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <div class="card-body p-2">
          <h3 class="card-title">Ordinance</h3>
          <div class="d-flex justify-end mb-4">
            <VBtn class="ml-auto" href="/ordinances/create"> Add </VBtn>
          </div>
          <div class="d-flex justify-space-between">
            <div class="col-auto">
              <div class="d-flex align-items-center gap-2">
                <div class="w-auto">Show</div>
                <div class="flex-1">
                  <select
                    class="form-select"
                    :value="serverQuery.perPage"
                    @input="handleServerQuery('perPage', $event.target.value)"
                  >
                    <option
                      v-for="i in [5, 10, 25, 50, 100]"
                      :value="String(i)"
                      :key="i"
                    >
                      {{ i }}
                    </option>
                  </select>
                </div>
                <div class="w-auto">entries</div>
              </div>
            </div>

            <div class="" style="min-width: 200px">
              <VTextField
                type="search"
                placeholder="Search"
                class="form-control"
                :value="serverQuery.query"
                @input="handleServerQuery('query', $event.target.value)"
              />
            </div>
          </div>
        </div>
        <VTable density="compact" class="text-no-wrap">
          <thead>
            <tr>
              <th class="text-uppercase text-left">Date</th>
              <th class="text-uppercase text-center">Title</th>
              <th class="text-uppercase text-center">No.</th>
              <th class="text-uppercase text-center">Term</th>
              <th class="text-uppercase text-center">Status</th>
              <th class="text-uppercase">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in paginatedData.data" :key="item.dessert">
              <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                {{ item.date }}
              </td>
              <td
                class="text-center px-5 py-3 border-b border-gray-200 bg-white text-sm"
              >
                {{ item.title }}
              </td>
              <td
                class="text-center px-5 py-3 border-b border-gray-200 bg-white text-sm"
              >
                {{ item.ordinance_no }}
              </td>
              <td
                class="text-center px-5 py-3 border-b border-gray-200 bg-white text-sm"
              >
                {{ item.term }}
              </td>
              <td
                class="text-center px-5 py-3 border-b border-gray-200 bg-white text-sm"
              >
                {{ item.status }}
              </td>
              <td
                class="text-center px-5 py-3 border-b border-gray-200 bg-white text-sm"
              >
                <a
                  href="javascript:void(0)"
                  class="text-red-500"
                  @click="deletePromise(item.id)"
                >
                  <v-icon class="text-danger"> mdi-trash </v-icon></a
                >
                <a
                  href="javascript:void(0)"
                  class="text-red-500"
                  @click="print(item.id)"
                >
                  <v-icon color="secondary" class="text-danger"> mdi-eye </v-icon></a
                >
                <a
                 
                  class="text-red-500"
                  :href="route('ordinances.edit', item.id)"
                >
                  <v-icon color="primary" class="text-danger"> mdi-edit </v-icon></a
                >
              </td>
              
            </tr>
          </tbody>
        </VTable>
      </div>
      <div
        class="card-footer py-3 w-100"
        v-if="paginatedData && paginatedData.meta.links"
      >
        <div class="d-flex justify-space-between">
          <div class="col-auto">
            <div class="table_info">
              Showing {{ paginatedData.meta.from }} to
              {{ paginatedData.meta.to }} of
              {{ paginatedData.meta.total }} entries
            </div>
          </div>
          <div class="col-auto">
            <nav
              class="dataTables_paginate paging_simple_numbers"
              aria-label="Page navigation example"
            >
              <ul class="pagination mb-0 d-flex">
                <li
                  class="page-item mx-2"
                  v-for="link in paginatedData.meta.links"
                  :key="link"
                >
                  <a
                    :is="link.url ? 'inertia-link' : 'button'"
                    class="page-link cursor-pointer"
                    :class="{
                      active: link.active,
                    }"
                    :href="link.url"
                    :only="['data', 'params']"
                  >
                    <span v-html="link.label"></span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
      <!-- Modal to add details -->

      <!-- Rest of your content -->
    </div>
  </AuthenticatedLayout>
</template>
