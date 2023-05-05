<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'
    import { useRoute, useRouter } from 'vue-router'
    import { biList, biArrowDownShort } from '@quasar/extras/bootstrap-icons'

    const route = useRoute()
    const router = useRouter()
    const logoutUrl = import.meta.env.VITE_API_URL + '/auth/logout'
    const user = ref({})
    const title = ref('')

    const logout = function() {
        axios.get('auth/logout')
            .then((response) => {
                if (response.data.data.success === true) {
                    router.push(`/auth/login`)
                }
            })
            .catch((error) => {
                console.log(error);
            })
    }

    axios.get('user')
        .then((response) => {
            if (response.data.data.success === true) {
                user.value = response.data.data.data
            } else {
                router.push(`/auth/login`)
            }
        })
        .catch((error) => {
            console.log(error);
            router.push(`/auth/login`)
        })

    onMounted(async () => {
        await router.isReady()
        title.value = route.meta.title || ''
    })

    const leftDrawerOpen = ref(false)

    function toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value
    }

    const menu = [
        {
            label: 'Организации',
            children: [
                {
                    to: '/account/companies',
                    label: 'Все организации',
                },
                {
                    to: '/account/company/new',
                    label: 'Добавить новую организацию',
                },
            ]
        }
    ]
</script>


<template>
    <q-layout view="lHh Lpr lFf" class="bg-white">
        <q-header elevated>
            <q-toolbar>
                <q-btn
                    flat
                    dense
                    round
                    @click="toggleLeftDrawer"
                    :icon="biList"
                />
                <q-toolbar-title>HR PLUS</q-toolbar-title>

                <q-btn-dropdown stretch flat :label="user.email" :dropdown-icon="biArrowDownShort" class="text-lowercase">
                    <q-list>
                        <q-item tabindex="0">
                            <!-- <q-item-section avatar>
                                <q-avatar icon="folder" color="secondary" text-color="white" />
                            </q-item-section> -->
                            <q-item-section>
                                <q-item-label>Личный профиль</q-item-label>
                                <!-- <q-item-label caption>February 22, 2016</q-item-label> -->
                            </q-item-section>
                        </q-item>
                        <q-item  tabindex="0">
                            <q-item-section @click="logout">
                                <q-item-label>Выход</q-item-label>
                            </q-item-section>
                        </q-item>    
                    </q-list>
                </q-btn-dropdown>
            </q-toolbar>
        </q-header>
    
        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
        >
            <q-tree
                :nodes="menu"
                node-key="label"
                default-expand-all
            >
                <template v-slot:default-header="prop">
                    <router-link v-if="prop.node.to" :to="prop.node.to">{{ prop.node.label }}</router-link>
                    <div v-else>{{ prop.node.label }}</div>
                </template>
            </q-tree>
        </q-drawer>

        <q-page-container class="bg-grey-1">
            <div class="acc-subheader bg-primary text-white">
                <h1 class="text-h5 text-weight-medium">{{ title }}</h1>
            </div>

            <div class="acc-main">
                <slot></slot>
            </div>
        </q-page-container>
    </q-layout>
</template>


<style lang="scss">
    .q-layout__shadow:after {
        box-shadow: none;
    }

    .q-page-container {
        min-height: 100vh;
    }

    .acc-subheader {
        padding: 2rem 18px 6rem;

        h1 {
            margin: 0;
        }
    }

    .acc-main {
        position: relative;
        margin: -45px 1.5rem 0;
    }

    .box {
        position: relative;
        padding: 1.25rem;
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.03);

        h2:first-child {
            margin-top: 0;
        }
    }
</style>