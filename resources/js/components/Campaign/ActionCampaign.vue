<template>
    <div>
        <v-alert :type="alert.alert_variant"
            v-if="alert.show_alert" class="my-2"
            >
            {{ alert.alert_msg }}
        </v-alert>
        <v-card color="white">
            <v-card-title>{{ statusButton == "add" ? "Add Campaign" : "Update Campaign" }}</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                <v-text-field v-model="campaign.title" :rules="titleRules" label="Title">
                </v-text-field>

                <v-text-field v-model="campaign.required" :rules="requiredRules" label="Required" type="number">
                </v-text-field>

                <v-text-field v-model="campaign.address" :rules="addressRules" label="Address">
                </v-text-field>

                <v-textarea v-model="campaign.description" :rules="descriptionRules" label="Description">
                </v-textarea>

                <v-text-field v-model="campaign.image" name="image" ref="image" label="Image" type="file">
                </v-text-field>

                <div class="d-flex flex-column">
                    <v-btn color="success" block class="mr-4" @click="submit">
                    {{ statusButton }}
                    </v-btn>
                </div>
            </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>

import { useUserStore } from '@/store/user';

export default {
    setup(){
        const userStore = useUserStore();
        return { userStore };
    },
    emits: ["closeDialog" , "reload"],
    props:{
        statusButton:{
            type: String,
            default: null
        },
        campaignItem: {
            type: Object,
            default: null
        }
    },
    created(){
        if(this.campaignItem){
            this.campaign= {
                title: this.campaignItem.title,
                required: this.campaignItem.required,
                address: this.campaignItem.address,
                description: this.campaignItem.description,
                colected: this.campaignItem.colected
            }
        }
    },
    data(){
        return {
            alert: {
            show_alert: false,
            alert_variant: "",
            alert_msg: null 
            },
            campaign: {
                title: "",
                required: "",
                address: "",
                description: "",
                colected: 0
            },
            valid: true,
            titleRules: [(v) => !!v || "Title is required"],
            requiredRules: [(v) => !!v || "Required is required"],
            addressRules: [(v) => !!v || "Address is required"],
            descriptionRules: [(v) => !!v || "Description is required"],
        }
    },
    methods:{
        async submit() {
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.alert.show_alert=true;
                this.alert.alert_variant="warning";
                this.alert.alert_msg="Please wait!";

                try {
                    const token = this.userStore.token; 

                    let image = this.$refs.image.files[0];
                    let formData = new FormData();
                    formData.append('title', this.campaign.title);
                    formData.append('required', this.campaign.required);
                    formData.append('address', this.campaign.address);
                    formData.append('description', this.campaign.description);
                    formData.append('colected', this.campaign.colected);

                   if(image != undefined){
                        formData.append('image', image)
                   } 

                   if(this.statusButton == "add"){
                        const config = {
                        headers: { Authorization: `Bearer ${token}`},
                        };

                    const DataResponse = await axios.post('/api/campaign', formData, config);

                    this.alert.show_alert=true;
                    this.alert.alert_variant="success";
                    this.alert.alert_msg= DataResponse.data.response_message;
                   } else {
                        const config = {
                            headers: { Authorization: `Bearer ${token}`},
                            params: { _method: "PUT" },
                        };

                    const DataResponse = await axios.post(`/api/campaign/${this.campaignItem.id}`, formData, config);

                    this.alert.show_alert=true;
                    this.alert.alert_variant="success";
                    this.alert.alert_msg= DataResponse.data.response_message;
                   }   
                   this.close();
                } catch (error) {
                    console.log(error)
                }
            }
        },
        async close(){
            await this.$emit('reload');
            await this.$emit('closeDialog');
        },
    },
}
</script>

<style lang="scss" scoped>
</style>