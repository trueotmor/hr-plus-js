<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { mdiMenuDown, mdiMenu, mdiAccountDetails, mdiChevronRight } from '@quasar/extras/mdi-v6'

const route = useRoute()
const router = useRouter()
const user = ref({})
const title = ref('')

const logout = function () {
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

function toggleLeftDrawer () {
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
                <q-btn flat dense round @click="toggleLeftDrawer" :icon="mdiMenu" />
                <q-toolbar-title>HR PLUS</q-toolbar-title>

                <q-btn-dropdown stretch flat :dropdown-icon="mdiMenuDown" class="text-lowercase">
                    <template v-slot:label>
                        <div class="row items-center no-wrap q-gutter-x-sm">
                            <q-avatar color="white" text-color="primary">A</q-avatar>
                            <div>{{ user.email }}</div>
                        </div>
                    </template>
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
                        <q-item tabindex="0">
                            <q-item-section @click="logout">
                                <q-item-label>Выход</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
            </q-toolbar>
        </q-header>

        <q-drawer v-model="leftDrawerOpen" show-if-above :width="250" bordered>
            <q-tree :nodes="menu" node-key="label" default-expand-all no-connectors :icon="mdiChevronRight">
                <template v-slot:default-header="prop">
                    <router-link v-if="prop.node.to" :to="prop.node.to">{{ prop.node.label }}</router-link>
                    <div v-else>
                        <q-icon :name="mdiAccountDetails" class="" />
                        {{ prop.node.label }}
                    </div>
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

        <q-footer bordered class="bg-white text-black">
            <q-toolbar>
                <q-toolbar-title>
                    <div>footer</div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>

    </q-layout>
</template>


<style lang="scss">
.q-layout__shadow:after {
    box-shadow: none;
}

.q-page-container {
    min-height: 100vh;
}

.q-tree {
    color: #4B4B5A;
}

.q-tree__node-header {
    flex-direction: row-reverse;
    padding: 12px 30px;
    font-size: medium;
    border-left: 3px solid transparent;

    &-content {
        color: #4B4B5A;

        & a {
            text-decoration: none;
            color: #4B4B5A;
        }

        & .q-icon {
            width: 16px;
            height: 16px;
            margin: 0 10px 0 3px;

            & svg {
                stroke: #4B4B5A;
                color: #4B4B5A;
            }
        }
    }


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