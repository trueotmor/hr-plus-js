<script setup>
    import { ref, onMounted, watch, watchEffect } from 'vue'
    import { useRoute, useRouter, onBeforeRouteUpdate } from 'vue-router'
    import axios from 'axios'
    import { useQuasar } from 'quasar'
    import { mdiAccount, mdiTextBoxOutline, mdiFileDocument, mdiEye } from '@quasar/extras/mdi-v6'
    import AccountBase from './AccountBase.vue'

    const route = useRoute()
    const router = useRouter()
    const $q = useQuasar()

    const loading = ref(true)
    const form = ref({
        description: ''
    })
    const contactForm = ref({})
    const logo = ref(null)
    const id = ref(0)
    const tab = ref('edit')
    const nextTab = ref(null)
    const formEl = ref(null)
    
    const submit = function() {
        loading.value = true;

        const headers = {}
        let data = { id: id.value }

        if (tab.value == 'edit') {
            data = form.value
        }
        if (tab.value == 'contacts') {
            data.contacts = JSON.stringify(contactForm.value)
        }
        if (tab.value == 'logo') {
            headers['Content-Type'] = "multipart/form-data"
            data.logo = logo.value
        }

        axios.post(`company/update`, data, { headers })
            .then((response) => {
                getCompany()
                $q.notify({ message: 'Данные сохранены', color: 'positive' })
                //router.push(`/account/company/${id.value}/${nextTab.value}`)
            })
            .catch((error) => {
                console.log(error);
            })
    }

    const getCompany = function() {
        axios.get(`company/${id.value}`)
            .then((response) => {
                if (response.data.data.success) {
                    form.value = response.data.data.data
                    form.value.description = form.value.description || ''
                    contactForm.value = JSON.parse(response.data.data.data.contacts || '{}')
                    loading.value = false;
                    if (nextTab.value) {
                        tab.value = nextTab.value
                    }
                } else {
                    alert('Компания не найдена')
                }
            })
            .catch((error) => {
                console.log(error);
            })
    }

    onMounted(async () => {
        await router.isReady()
        id.value = +route.params.id || 0
        //tab.value = route.params.tab || 'edit'
        //console.log(tab.value)
        getCompany()
    })

    const changeTab = function(to) {
        nextTab.value = to
        if (formEl.value) {
            formEl.value.submit()
        } else {
            tab.value = nextTab.value
        }
    }

    onBeforeRouteUpdate(async (to, from) => {
        console.log(to)
        if (to.params.id !== from.params.id) {
            
        }
    })

    const fields = [
        { name: 'name', label: 'Наименование организации', rules: [ val => val && val.length > 0 || 'Заполните это поле'] },
        { name: 'brand', label: 'Бренд' },
        { name: 'address', label: 'Адрес организации' },
        { name: 'phone', label: 'Рабочий телефон', type: 'tel' },
        { name: 'site', label: 'Сайт организации' },
        { name: 'email', label: 'E-mail организации', type: 'email' },
        { name: 'inn', label: 'ИНН' },
        { name: 'ogrn', label: 'ОГРН' }
    ]

    const contactsFields = [
        { name: 'group_vk', label: 'ВКонтакте' },
        { name: 'group_ok', label: 'Одноклассники' },
        { name: 'group_other', label: 'Другие профили (через запятую)' },
        { name: 'group_tg', label: 'Телеграм канал' },
        { name: 'msg_vk', label: 'ВКонтакте' },
        { name: 'msg_wa', label: 'WhatsApp' },
        { name: 'msg_skype', label: 'Skype' },
        { name: 'msg_viber', label: 'Viber' },
    ]
</script>

<template>
    <account-base>
        <q-inner-loading :showing="loading"/>

        <div class="row q-col-gutter-lg">
            <div class="company-edit__col col-2 q-gutter-lg">
                <div>
                    <button @click="changeTab('edit')" :class="tab == 'edit' && 'bg-primary text-white'" class="box">
                        <q-icon :name="mdiTextBoxOutline" size="2rem"/>
                        <div>Данные о компании</div>
                    </button>
                </div>
                <div>
                    <button @click="changeTab('contacts')" :class="tab == 'contacts' && 'bg-primary text-white'" class="box">
                        <q-icon :name="mdiAccount" size="2rem"/>
                        <div>Все контакты</div>
                    </button>
                </div>
                <div>
                    <button @click="changeTab('logo')" :class="tab == 'logo' && 'bg-primary text-white'" class="box">
                        <q-icon :name="mdiFileDocument" size="2rem"/>
                        <div>Загрузить логотип</div>
                    </button>
                </div>
                <div>
                    <button @click="changeTab('view')" :class="tab == 'view' && 'bg-primary text-white'" class="box">
                        <q-icon :name="mdiEye" size="2rem"/>
                        <div>Просмотр</div>
                    </button>
                </div>
            </div>

            <div class="company-edit__col col-10">
                <q-form v-if="tab == 'edit'" @submit="submit" ref="formEl" class="box">
                    <h2 class="text-h6">Введите общие данные организации:</h2>

                    <div class="row q-col-gutter-lg">
                        <template v-for="i in fields">
                            <div class="col-6">
                                <q-input v-model="form[i.name]" outlined :name="i.name" :type="i.type || 'text'" :label="i.label"
                                    :rules="i.rules || null"
                                    :hide-bottom-space="true"
                                    lazy-rules
                                />
                            </div>
                        </template>

                        <div class="col-12">
                            <p>Описание организации</p>
                            <q-editor v-model="form.description" min-height="5rem" />
                        </div>

                        <!-- <div class="col-12">
                            <q-btn label="Сохранить" type="submit" color="primary"/>
                        </div> -->
                    </div>
                </q-form>

                <q-form v-if="tab == 'contacts'" @submit="submit" ref="formEl" class="box" action="">
                    <h2 class="text-h6">Группы в соцсетях и каналы в мессенджерах:</h2>
                    <div class="row q-col-gutter-lg">
                        <template v-for="i in contactsFields">
                            <div v-if="i.name.startsWith('group_')" class="col-6">
                                <q-input v-model="contactForm[i.name]" outlined :name="i.name" :label="i.label"/>
                            </div>
                        </template>
                    </div>

                    <h2 class="text-h6">Мессенджеры:</h2>
                    <div class="row q-col-gutter-lg">
                        <template v-for="i in contactsFields">
                            <div v-if="i.name.startsWith('msg_')" class="col-6">
                                <q-input v-model="contactForm[i.name]" outlined :name="i.name" :label="i.label"/>
                            </div>
                        </template>

                        <!-- <div class="col-12">
                            <q-btn label="Сохранить" type="submit" color="primary"/>
                        </div> -->
                    </div>
                </q-form>

                <q-form v-if="tab == 'logo'" @submit="submit" ref="formEl" class="box "  action="">
                    <h2 class="text-h6">Загрузить логотип организации:</h2>
                    <div class="row q-col-gutter-lg">
                        <div class="col-12">
                            <img :src="form.logo" alt="" width="150">
                        </div>
                        <div class="col-12">
                            <q-file
                                name="logo"
                                v-model="logo"
                                outlined
                                label="Загрузить логотип"
                            />
                        </div>
                        <!-- <div class="col-12">
                            <q-btn label="Сохранить" type="submit" color="primary"/>
                        </div> -->
                    </div>
                </q-form>

                <div class="row q-mt-lg">
                    <template v-if="tab == 'edit'">
                        <q-btn label="Назад" color="grey" disabled/>
                        <q-btn @click="changeTab('contacts')" label="Вперёд" color="primary"/>
                    </template>
                    <template v-else-if="tab == 'contacts'">
                        <q-btn @click="changeTab('edit')" label="Назад" color="grey"/>
                        <q-btn @click="changeTab('logo')" label="Вперёд" color="primary"/>
                    </template>
                    <template v-else-if="tab == 'logo'">
                        <q-btn @click="changeTab('contacts')" label="Назад" color="grey"/>
                        <q-btn @click="changeTab('view')" label="Вперёд" color="primary"/>
                    </template>
                    <template v-else-if="tab == 'view'">
                        <q-btn @click="changeTab('logo')" label="Назад" color="grey"/>
                        <q-btn @click="changeTab('edit')" label="OK" color="positive"/>
                    </template>
                </div>

            </div>

        </div>
        
    </account-base>
</template>

<style lang="scss" scoped>
    .company-edit__col {
        padding-top: 0;
    }
    button.box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        border: none;
        cursor: pointer;
        text-align: center;

        &.router-link-active {
            background-color: var(--q-primary);
            color: #fff;
        }
    }
</style>