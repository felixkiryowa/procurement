<template>
  <div>
    <NavBarComponent :appName="app" :user="user" />
    <br />
    <br />
    <br />
    <div class="container-fluid">
      <div class="vld-parent">
        <loading
          :active.sync="isLoading"
          :can-cancel="false"
          color="#074578"
          loader="spinner"
          :opacity="0.5"
          :is-full-page="fullPage"
        />
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Upload Beneficiaries</h5>
            </div>

            <div class="card-body custom-card-body">
              <div>
                <a
                  :href="downloadTemplate()"
                  download
                  class="btn btn-success float-right"
                >
                  Download Template
                </a>
              </div>
              <br />
              <br>
              <hr />
              <form @submit.prevent="uploadBeneficiariesFile" role="form">
                <div class="row">
                  <div class="col-md-3">
                    <label for="exampleFormControlTextarea1"
                      >Select an Item Code</label
                    >
                    <v-select
                      label="name"
                      :options="all_accounts"
                      :class="{
                        'is-invalid': form.errors.has('item_code'),
                      }"
                      v-model="form.item_code"
                    ></v-select>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleFormControlTextarea1"
                        >Upload Beneficiaries Excel File</label
                      >
                      <input
                        type="file"
                        @change="handleFileUpload"
                        ref="file"
                        class="form-control"
                      />
                    </div>
                    <br />
                    <button
                      type="submit"
                      :disabled="!isUploadedFileExcel"
                      class="btn btn-success float-right"
                    >
                      Upload Excel File
                    </button>
                  </div>
                  <div class="col-md-3"></div>
                </div>
              </form>
            </div>
          </div>
          <br />
          <br />
        </div>
      </div>
    </div>

    <h1 hidden>{{ checkIfUserIsIdle }}</h1>
  </div>
</template>

<script>
import NavBarComponent from "../NavBar/NavBarComponent.vue";
import Loading from "vue-loading-overlay";
import { mapActions, mapState, mapGetters } from "vuex";
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
export default {
  props: {
    all_accounts: Array,
  },
  data() {
    return {
      fullPage: true,
      isUploadedFileExcel: false,
      file_attachment: "",
      form: new Form({
        file_attachment: "",
        item_code: "",
      }),
    };
  },
  mounted() {
    $("#allSubSubBankAccounts").DataTable();
  },
  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    app() {
      return this.$page.props.appName;
    },
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },

    ...mapState("chartofaccounts", ["isLoading"]),
  },

  methods: {
    ...mapActions("chartofaccounts", ["showLoader", "hideLoader"]),

    handleFileUpload() {
      this.showLoader();
      setTimeout(() => {
        this.hideLoader();
      }, 2000);
      this.file_attachment = this.$refs.file.files[0];
      let extension = this.file_attachment.name.substring(
        this.file_attachment.name.lastIndexOf(".") + 1
      );

      if (extension === "xlsx") {
        this.isUploadedFileExcel = true;
      } else {
        Swal.fire({
          icon: "error",
          title: "Invalid Uploaded File Format",
          text: "Uploaded Attachment Should Be An Excel File",
        });
      }
    },
    downloadTemplate() {
       let beneficiary_list = "/img/beneficiary_list.xlsx";
       return beneficiary_list;
    },

    uploadBeneficiariesFile() {
      this.$Progress.fail();
      this.showLoader();

      if (this.form.item_code === "") {
        alert("Item code is required");
        this.hideLoader();
      } else {
        const formData = new FormData();
        formData.append("uploaded_attachment", this.file_attachment);
        formData.append("item_code", this.form.item_code.account_number);
        const headers = { "Content-Type": "multipart/form-data" };
        axios
          .post("/upload", formData, { headers })
          .then((response) => {
            Swal.fire({
              icon: "success",
              title: "Uploading Beneficiaries",
              text: response.data.message,
            });
            this.hideLoader();
            window.location.href = "/list/entries";
          })
          .catch((error) => {
            this.$Progress.fail();
            this.hideLoader();
          });
      }
    },
  },
  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 2000);
  },

  components: {
    NavBarComponent,
    Loading,
  },
};
</script>
