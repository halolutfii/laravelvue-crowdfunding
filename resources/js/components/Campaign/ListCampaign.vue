<template>
     <v-alert :type="alert.alert_variant"
            v-if="alert.show_alert" class="my-2"
            >
            {{ alert.alert_msg }}
    </v-alert>
    <div class="d-flex justify-content-end">
        <v-btn class="md-1" icon="mdi-heart" color="success" size="x-large" @click="getAdd()">
            <v-icon size="x-large">mdi-plus-circle</v-icon>
            <v-tooltip activator="parent" location="top">Add</v-tooltip>
        </v-btn>
         <v-dialog transition="dialog-bottom-transition" max-width="600" v-model="dialogActionAdd">
            <action-campaign statusButton="add" @closeDialog="dialogActionAdd=false" @reload="Reload"></action-campaign>
        </v-dialog>
    </div>
    <v-table>
        <thead>
            <tr>
                <th class="text-left">
                    Title
                </th>
                <th class="text-left">
                    Required
                </th>
                <th class="text-left">
                    Colected
                </th>
                <th class="text-left">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in campaign" :key="item">
            <td>{{ item.title }}</td>
            <td>{{ item.required }}</td>
            <td>{{ item.colected }}</td>
            <td>
                <!-- Detail -->
                <v-btn size="small" class="m-2" color="info" @click="getDetail(item)">
                    <v-icon>mdi-information-outline</v-icon>
                    <v-tooltip activator="parent" location="top">Detail</v-tooltip>
                </v-btn>
                <!-- Update -->
                <v-btn size="small" class="m-2" color="warning" @click="getUpdate(item)">
                    <v-icon>mdi-pencil</v-icon>
                    <v-tooltip activator="parent" location="top">Update</v-tooltip>
                </v-btn>
                <!-- Delete -->
                <v-btn size="small" class="m-2" color="red" @click="getDelete(item)">
                    <v-icon>mdi-delete</v-icon>
                    <v-tooltip activator="parent" location="top">Delete</v-tooltip>
                </v-btn>
            </td>
            </tr>
        </tbody>
    </v-table>
        <!-- Detail -->
        <v-dialog transition="dialog-bottom-transition" max-width="600" v-model="dialogActionDetail">
            <detail-campaign :campaignItem="showData" statusButton="detail" @closeDialog="dialogActionDetail=false" @reload="Reload"></detail-campaign>
        </v-dialog>
        <!-- Update -->
        <v-dialog transition="dialog-bottom-transition" max-width="600" v-model="dialogActionUpdate">
            <action-campaign :campaignItem="showData" statusButton="update" @closeDialog="dialogActionUpdate=false" @reload="Reload"></action-campaign>
        </v-dialog>
        <!-- Delete -->
        <v-dialog persistent max-width="400" v-model="dialogActionDelete" @reload="Reload">
            <v-card>
                <v-card-title class="text-h4 text-center">
                    <v-icon color="red">mdi-information-outline</v-icon>
                </v-card-title>
                <v-card-text class="text-center">
                    <div class="text-h6">Apakah anda yakin ingin menghapus data {{ showData.title }}?</div>
                </v-card-text>
                <v-card class="text-center">
                    <v-spacer></v-spacer>
                    <v-btn color="grey" variant="text" @click="dialogActionDelete=false">
                        <div class="text-black">NO</div>
                    </v-btn>
                    <v-btn color="red" variant="text" @click="deleteData()">
                        <div>YES</div>
                    </v-btn>
                </v-card>
            </v-card>
        </v-dialog>
</template>
<script>
import ActionCampaign from './ActionCampaign.vue';
import DetailCampaign from './DetailCampaign.vue';
import { useUserStore } from '@/store/user';

export default{
    setup(){
        const userStore = useUserStore();
        return { userStore };
    },
    data(){
        return {
            alert: {
            show_alert: false,
            alert_variant: "",
            alert_msg: null 
            },
            dialogActionAdd: false,
            dialogActionUpdate: false,
            dialogActionDetail: false,
            dialogActionDelete: false,
            showData: null
        }
    },
    components:{
        ActionCampaign,
        DetailCampaign,
    },
    emits: ['reload'],
    props:{
        campaign: Object,
    },
    methods: {
        getAdd(){
            this.dialogActionAdd = true
        },
        getUpdate(item){
            this.dialogActionUpdate = true
            this.showData = item
        },
        getDetail(item){
            this.dialogActionDetail = true
            this.showData = item
        },
        getDelete(item){
            this.dialogActionDelete = true
            this.showData = item
        },
        Reload(){
            this.$emit('reload')
        },
        async deleteData(){
            this.alert.show_alert=true;
            this.alert.alert_variant="warning";
            this.alert.alert_msg="Please wait!";

            try {
                const token = this.userStore.token; 
                const config = {
                        method: "POST",
                        params: { _method: "Delete" },
                        url : `/api/campaign/${this.showData.id}`,
                        headers: { Authorization: `Bearer ${token}`},
                        };

                const DataResponse = await axios(config);

                this.alert.show_alert=true;
                this.alert.alert_variant="success";
                this.alert.alert_msg= DataResponse.data.response_message;

                this.dialogActionDelete= false;
                this.Reload();
            } catch (err) {
                console.log(err);
            }
        },
    },
}
</script>
