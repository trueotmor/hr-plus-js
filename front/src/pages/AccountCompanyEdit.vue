<script setup>
    import { ref, onMounted, watch } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import axios from 'axios'
    import { useQuasar } from 'quasar'
    import { biPersonFill, biFileEarmarkImage, biEye, biCardText } from '@quasar/extras/bootstrap-icons'
    import AccountBase from './AccountBase.vue'

    const route = useRoute()
    const router = useRouter()
    const $q = useQuasar()

    const form = ref({
        description: ''
    })
    const contactForm = ref({})
    const logo = ref(null)
    const id = ref(0)
    const tab = ref('edit')
    
    const submit = function() {
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
                $q.notify({ message: 'Данные обновлены', color: 'positive' })
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
                } else {
                    alert('Компания не найдена')
                }
            })
            .catch((error) => {
                console.log(error);
            })
    }

    onMounted(async () => {
        console.log('mounted')
        await router.isReady()
        id.value = +route.params.id || 0
        tab.value = route.params.tab || 'edit'

        getCompany()
    })

    watch(
        () => route.params.tab, async val => {
            tab.value = val
        }
    )

    const fields = [
        { name: 'name', label: 'Наименование организации', required: true },
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
        <div class="row q-col-gutter-lg">
            <div class="company-edit__col col-2 q-gutter-lg">
                <router-link :to="`/account/company/${id}/edit`" class="box">
                    <q-icon :name="biCardText" size="2rem"/>
                    <div>Данные о компании</div>
                </router-link>
                <router-link :to="`/account/company/${id}/contacts`" class="box">
                    <q-icon :name="biPersonFill" size="2rem"/>
                    <div>Все контакты</div>
                </router-link>
                <router-link :to="`/account/company/${id}/logo`" class="box">
                    <q-icon :name="biFileEarmarkImage" size="2rem"/>
                    <div>Загрузить логотип</div>
                </router-link>
                <router-link :to="`/account/company/${id}/view`" class="box">
                    <q-icon :name="biEye" size="2rem"/>
                    <div>Просмотр</div>
                </router-link>
            </div>

            <div class="company-edit__col col-10">
                <q-form v-if="tab == 'edit'" @submit="submit" class="box">
                    <h2 class="text-h6">Введите общие данные организации:</h2>

                    <div class="row q-col-gutter-lg">
                        <template v-for="i in fields">
                            <div class="col-6">
                                <q-input v-model="form[i.name]" outlined :name="i.name" :type="i.type || 'text'" :label="i.label" :required="i.required"/>
                            </div>
                        </template>

                        <div class="col-12">
                            <p>Описание организации</p>
                            <q-editor v-model="form.description" min-height="5rem" />
                        </div>

                        <div class="col-12">
                            <q-btn label="Сохранить" type="submit" color="primary"/>
                        </div>
                    </div>
                </q-form>

                <q-form v-if="tab == 'contacts'" @submit="submit" class="box" action="">
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

                        <div class="col-12">
                            <q-btn label="Сохранить" type="submit" color="primary"/>
                        </div>
                    </div>
                </q-form>

                <q-form v-if="tab == 'logo'" @submit="submit" class="box "  action="">
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
                        <div class="col-12">
                            <q-btn label="Сохранить" type="submit" color="primary"/>
                        </div>
                    </div>
                </q-form>
            </div>
        </div>
        
    </account-base>
</template>

<style lang="scss" scoped>
    .company-edit__col {
        padding-top: 0;
    }
    a.box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: inherit;
        text-decoration: none;

        &.router-link-active {
            background-color: var(--q-primary);
            color: #fff;
        }
    }
</style>