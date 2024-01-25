<template>
    <v-layout class="rounded rounded-md">
        <v-navigation-drawer color="info" app v-model="drawer">
            <v-list>
                <v-list-item v-if="userStore.isLogin"
                  prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
                  :title="userStore.user.name"
                >
                </v-list-item>
            </v-list>
            <div class="pa-2" v-if="!userStore.isLogin">
                <v-btn block color="primary" class="mb-1" @click="dialog=true">
                    <v-icon>mdi-lock</v-icon>
                    Login / Register
                </v-btn>
            </div>

            <v-list density="compact" nav>
            <v-list-item prepend-icon="mdi-view-dashboard" title="Home" value="Home" to="/"></v-list-item>
            <v-list-item v-if="userStore.isAdmin" prepend-icon="mdi-forum" title="Campaign" value="Campaign" :to="{name: 'campaign'}"></v-list-item>
            <v-list-item v-if="userStore.isNotVerification" prepend-icon="mdi-account-alert" title="Verification Account" value="Verification" :to="{name:'verification'}"></v-list-item>
            </v-list>

            <template v-slot:append>
                <div class="pa-2" v-if="userStore.isLogin">
                <v-btn block color="red" @click.prevent="logout()">
                    <v-icon>mdi-lock</v-icon>
                    Logout
                </v-btn>
            </div>
            </template>
        </v-navigation-drawer>

        <v-app-bar color="info" app>
            <template v-slot:prepend>
            <v-app-bar-nav-icon @click.stop="drawer=!drawer">

            </v-app-bar-nav-icon>
            </template>

            <v-toolbar-title>
                <v-icon >mdi-charity</v-icon>
                Charity App
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>mdi-magnify</v-icon>
            </v-btn>

            <v-btn icon>
                <v-badge color="orange" overlap>
                    <template v-slot:badge>
                        <span>3</span>
                    </template>
                    <v-icon>mdi-cash-multiple</v-icon>
                </v-badge>
            </v-btn>
        </v-app-bar>
         

        <v-main class="d-flex align-center justify-center" style="min-height: 300px">
            <v-col cols="auto">
                <v-dialog
                transition="dialog-bottom-transition"
                width="600"
                v-model="dialog"
                >
                <tab-auth @closeDialog="closeDialog"></tab-auth>
            </v-dialog>
            </v-col>
        <router-view></router-view>
        </v-main>
    </v-layout>

</template>

<script>
import TabAuth from './components/Auth/TabAuth.vue'
import { useUserStore } from './store/user'
export default{
    setup() {
        const userStore = useUserStore();
        return {userStore};
    },
    name: 'App',
    data: () => ({
        drawer :false,
        dialog : false 
    }),
    components: {
        TabAuth
    },
    methods: {
        closeDialog(){
            this.dialog=false;
        },
        async logout(){
            await this.userStore.removeAuth();
            this.$router.push('/');
        },
    },
}
</script>