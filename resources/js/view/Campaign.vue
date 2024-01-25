<template>
    <v-card class="mt-5 rounded border-1 border-black" width="1000" prepend-icon="mdi-hand-heart-outline">
        <template v-slot:title>
            Campaign
        </template>
        <v-card-text>
            <!-- Component tampil campaign -->
            <list-campaign :campaign="campaign" @reload="getCampaign"/>
        </v-card-text>
    </v-card>
</template>
<script>
import ListCampaign from '../components/Campaign/ListCampaign.vue';
import { useUserStore } from '@/store/user';

export default{
    setup(){
        const userStore = useUserStore();
        return { userStore };
    },
    components:{
        ListCampaign,
    },
    data(){
        return {
            campaign: []
        }
    },
    methods:{
        async getCampaign(){
            try {
                const token = this.userStore.token;
                const config = {
                    headers: { Authorization: `Bearer ${token}`},
                };
                
                const data = await axios.get('/api/campaign', config);

                this.campaign = data.data.data.campaign;
                console.log(data);
            } catch (error) {
                console.log(error)
            }
        },
    },
    created(){
        this.getCampaign();
    },
}
</script>