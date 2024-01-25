<template>
    <div>
        <v-alert :type="alert.alert_variant"
        v-if="alert.show_alert" class="my-2"
        >
        {{ alert.alert_msg }}
        </v-alert>
    </div>
    <v-form ref="form" v-model="valid" lazy-validation>
        <!-- Email -->
        <v-text-field v-model="user.email" :rules="emailRules" label="E-mail" type="email">
        </v-text-field>
        <!-- Name -->
        <v-text-field v-model="user.name" :rules="nameRules" label="Name" type="text">
        </v-text-field>
        <!-- Password -->
        <v-text-field v-model="user.password" :rules="passwordRules" label="Password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :type="show1 ? 'text' : 'password'"  @click:append="show1 = !show1">
        </v-text-field>
        <!-- Confirmation Password -->
        <v-text-field v-model="user.password_confirmation" :rules="passwordRules" label="Password Confirmation" :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'" :type="show2 ? 'text' : 'password'"  @click:append="show2 = !show2 ">
        </v-text-field>

        <div class="d-flex flex-column">
            <v-btn color="success" block class="mr-4" @click="registerSubmit">
            Sign-Up
            </v-btn>
        </div>
    </v-form>
</template>
<script>
    import { useUserStore } from "@/store/user";
  export default {
    emits : [
      "closeDialog"
    ],
    setup() {
        const userStore = useUserStore();
        return {userStore};
    },
    data(){
        return {
            alert: {
                show_alert: false,
                alert_variant: "",
                alert_msg: null 
            },
    
            valid: true,
            show1: false,
            show2: false,
    
            user:{
            email: "",
            name: "",
            password: "",
            password_confirmation: "",
           },
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            nameRules: [
                v => !!v || 'Name is required',
            ],
            passwordRules: [
                v => !!v || 'Password is required',
                v => (v && v.length >= 8) || 'Min 8 character',
                (confirmation) => confirmation === this.user.password || 'Password must macth', 
            ],
            };
        },
        methods: {
        async registerSubmit() {
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.alert.show_alert=true;
                this.alert.alert_variant="warning";
                this.alert.alert_msg="Please wait!";
                try {
                    const authRegister = await axios.post(
                    '/api/auth/register', 
                    this.user
                    );

                    this.alert.show_alert=true;
                    this.alert.alert_variant="success";
                    this.alert.alert_msg= authRegister.data.response_message;

                    const dataUser = authRegister.data.data.user;
                    const token =  authRegister.data.token;
                    await this.userStore.setVerification(dataUser, token);

                    this.$emit("closeDialog");
                    await this.$router.push('/verification');
                } catch (error) {
                    console.log(error);
                }
            }
        },
    },
};
</script>