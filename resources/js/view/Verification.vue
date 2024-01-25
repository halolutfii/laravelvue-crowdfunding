<template>
    <v-card
    class="mx-auto m-5"
    max-width="700"
    variant="outlined"
    >
    <div>
        <v-alert :type="alert.alert_variant"
        v-if="alert.show_alert" class="my-2"
        >
        {{ alert.alert_msg }}
        </v-alert>
    </div>
      <v-card-item class="m-3">
        <div>
            <div class="text-h4 text-center my-2">
                Verification Email 
            </div>
            <div class="text-caption text-center my-3">
            </div>
        </div>
      </v-card-item>
      <v-card-text>
        <v-form ref="form" v-model="valid" lazy-validation>
        <!-- OTP Code -->
        <v-text-field v-model="otp" :rules="otpRules" label="OTP Code" type="number">
        </v-text-field> 
        <div class="d-flex flex-column">
            <v-btn color="success" block class="mr-4" @click="verificationSubmit">
            Generate
            </v-btn>
        </div>
    </v-form>
      </v-card-text>
      <v-card-text>
        <div class="text-caption text-center">
            Generate again OTP Code? <a href="#" @click="generateOTPCode">Click here!</a>
        </div>
      </v-card-text>
    </v-card>
</template>
<script>
  import { mapActions, mapWritableState } from 'pinia';
  import { useUserStore } from "@/store/user";
export default {
  // setup() {
  //     const userStore = useUserStore();
  //     return {userStore};
  // },
  data: () => ({
      alert: {
          show_alert: false,
          alert_variant: "",
          alert_msg: null 
      },
      valid: false, 
      otp: null,
      otpRules: [
        (v) => !!v == "OTP Code is required",
        (v) => (v && v.length <= 6) || "Max 6 Character!"
      ],
      }),
      computed: {
        ...mapWritableState(useUserStore, ['user']),
      },
      methods: {
        ...mapActions(useUserStore, ['verificationSuccess']),
        async verificationSubmit(){
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.alert.show_alert = true;
                this.alert.alert_variant = "warning";
                this.alert.alert_msg = "Please wait..";

                try {
                  const verification = await axios.post(
                    '/api/auth/verifikasi-email',
                    {
                      otp: this.otp,
                    }
                  );
                  
                  this.alert.show_alert = true;
                  this.alert.alert_variant = "success";
                  this.alert.alert_msg = verification.data.response_message;

                  this.$router.push('/');

                  this.verificationSuccess();
                } catch (error) {
                  this.alert.show_alert = true;
                  this.alert.alert_variant = "error ";
                  this.alert.alert_msg = error.response.data.response_message;
                }
            }
        },
        async generateOTPCode(){
                this.alert.show_alert = true;
                this.alert.alert_variant = "warning";
                this.alert.alert_msg = "Please wait..";

                try {
                  const generate = await axios.post(
                    '/api/auth/generate-otpcode',
                    {
                      email: this.user.email,
                    }
                  );
                  
                  this.alert.show_alert = true;
                  this.alert.alert_variant = "success";
                  this.alert.alert_msg = generate.data.response_message;
                } catch (error) {
                  console.log(error);
                }
        }
      },
};
</script>