let store = {
    state: {
        //смс
        id_sms: '',
        ERRORCode: false,
        SUCCCode: false,
        //смс
        info: "",
        typeOrder: '',
        menus: [{
            'title': 'Меню',
            'id': -1
        }],
        cart: [],
        ErrorCount: [],
        products: [],
        categories: [],
        showCategories: true,
        user: {
            value: '',
            text: ''
        },
        users: [],
        price: 0,
        skidka: 0,
        total: 0,

        //****************столы *******************
        tables: [],
        // активный стол на закрытие
        TableCloseActive: false,
        // активный стол на открытие
        TableAddActive: false
    },
    mutations: {
        CategoriesSet(state, data) {
            state.categories = data.categories;
        },
        ProductCategorySet(state, data) {
            state.products = data.products;
            state.showCategories = false;
        },
        // добавляем категорию
        MenyAddCategory(state, category) {
            state.menus = [{
                    'title': 'Меню',
                    'id': -1
                },
                {
                    'title': category.title,
                    'id': category.id
                }

            ];
        },
        // клик по пункту меню
        MenuClick(state, menu) {
            if (menu.id == -1) {
                state.menus = [{
                        'title': 'Меню',
                        'id': -1
                    }

                ];
                state.showCategories = true;
            }
        },
        //Корзина установить продукты и скидки при первоначальной загрузке*******************************
        SetCart(state, data) {
            state.cart = data.cart;
            state.user = data.user;
            state.skidka = data.skidka;
            state.typeOrder = data.typeOrder;
            state.info = data.info;

        },
        //Корзина*******************************
        AddCart(state, product) {
            let i = state.cart.map(item => item.id).indexOf(product.id);
            // продукт
            let j = state.products.map(item => parseInt(item.id)).indexOf(product.id);
            if (i == -1) {
                state.cart.push({
                    title: product.title,
                    id: product.id,
                    image: product.image,
                    price: product.price,
                    count: 1,
                    total: product.price,
                    countThis: product.count,
                    isOpen: false,
                    unlimited: product.unlimited
                });
                //изменяем к-во
                if (j !== -1) state.products[j].count = parseFloat(state.products[j].count) - 1;
            } else {
                let plusCount = 1;
                if (plusCount > state.products[j].count && state.cart[i].unlimited !== 1 && state.cart[i].unlimited !== '1') {
                    state.cart[i].isOpen = true;
                    setTimeout(() => {
                        state.cart[i].isOpen = false;
                    }, 2000)
                } else {
                    state.cart[i].count = parseFloat(state.cart[i].count) + 1;
                    //изменяем к-во
                    if (j !== -1) state.products[j].count = parseFloat(state.products[j].count) - 1;
                }
            }
        },
        AddPlusProduct(state, product) {
            let i = state.cart.map(item => parseInt(item.id)).indexOf(parseInt(product.id));
            // продукт
            let j = state.products.map(item => item.id).indexOf(product.id);
            let plusCount = 1;
            if (j !== -1) {
                if (plusCount > state.products[j].count && state.cart[i].unlimited !== 1 && state.cart[i].unlimited !== '1') {
                    state.cart[i].isOpen = true;
                    setTimeout(() => {
                        state.cart[i].isOpen = false;
                    }, 2000)
                } else {
                    state.cart[i].count = state.cart[i].count +plusCount;
                    //изменяем к-во
                    state.products[j].count = parseFloat(state.products[j].count) - plusCount;
                }
            } else {
                if (plusCount > state.cart[i].countThis && state.cart[i].unlimited !== 1 && state.cart[i].unlimited !== '1') {
                    state.cart[i].isOpen = true;
                    setTimeout(() => {
                        state.cart[i].isOpen = false;
                    }, 2000)
                } else {
                    state.cart[i].count = state.cart[i].count + plusCount;
                }

            }
        },
        // клик на минусе продукта
        AddMinusProduct(state, product) {
            let i = state.cart.map(item => parseInt(item.id)).indexOf(parseInt(product.id));
            // продукт
            let j = state.products.map(item => item.id).indexOf(product.id);
            if (state.cart[i].count > 0.5) {
                state.cart[i].count = parseFloat(state.cart[i].count) - 0.5;

            } else {
                // удаляем из корзины
                let i = state.cart.map(item => parseInt(item.id)).indexOf(parseInt(product.id));
                state.cart.splice(i, 1);
            }
            //изменяем к-во
            if (j !== -1) state.products[j].count = parseFloat(state.products[j].count) + 0.5;

        },
        CartClear(state) {
            state.cart = [];
        },
        // цена бара
        SetTotal(state) {
            let price = 0;
            state.cart.forEach((el, i) => {
                let r = el.count * el.price;
                state.cart[i].total = r;
                price += r;
            });
            state.price = price;
            if (state.skidka > 0) {
                var summaSkidki = state.skidka * state.price / 100;
                state.total = (state.price - summaSkidki).toFixed(2);
            } else {
                state.total = price;
            }

        },
        // цена открытых столов
        SetTotalTable(state, data) {
            state.price = data.priceOrder;
            state.total = data.priceOrderTotal;
        },
        //недостаточно кол-ва
        ErrorCount(state, data) {
            state.ErrorCount = data;
            $('#PayModal').modal('hide');
        },
        // установить пользователей
        UsersSet(state, users) {
            state.users = users;
        },
        // установить пользователя
        UserSet(state, user) {
            state.user = user;
        },
        // SMS
        SetSMS(state, id_sms) {
            state.id_sms = id_sms;
        },
        // проверка кода
        checkCode(state, data) {
            if (data.res == 1) {
                // ответ верный
                state.ERRORCode = false;
                let i = state.users.map(item => item.value).indexOf(state.user.value);
                if (state.typeOrder == "type_bar") {
                    state.skidka = state.users[i].skidka_bar;
                } else {
                    state.skidka = state.users[i].skidka;
                }
                state.SUCCCode = true;
            } else {
                state.SUCCCode = false;
                state.ERRORCode = true;
                state.skidka = 0;
            }
            setTimeout(() => {
                state.SUCCCode = false;
                state.ERRORCode = false;
            }, 300)
        },
        //установить все столы *************************************88
        SetTables(state, tables) {
            state.tables = tables;
        },

        // активный стол на закрытие
        SetTableactive(state, id) {
            if (id) {
                state.TableAddActive = false;
            }
            state.TableCloseActive = id;
        },

        // активный стол на добавление
        SetTableAddActive(state, id) {

            state.TableAddActive = id;
            state.TableCloseActive = false;

        },

        //установить цены скидки стола открытого для модалки
        SetTableTotaldata(state, data) {
            state.total = data.total;
            state.skidka = data.skidka;
            state.price = data.price;
        }

    },
    actions: {
        // получить все категории
        CategoriesGet({
            commit
        }) {
            return axios.get('/order/CategoriesGet')
                .then(response => {
                    commit('CategoriesSet', response.data);
                });
        },
        // получить продукты даннй категории
        CategoriesProducts({
            commit,
            state
        }, cat_id) {
            let i = state.categories.map(item => parseInt(item.id)).indexOf(parseInt(cat_id));
            let category = state.categories[i];
            commit('MenyAddCategory', category);
            return axios.post('/order/ProductCategoryGet', {
                    cat_id: cat_id
                })
                .then(response => {
                    commit('ProductCategorySet', response.data);
                });

        },
        SearchProduts({
            commit,
            state
        }, val) {
            return axios.post('/order/SearchProduts', {
                    val: val
                })
                .then(response => {
                    commit('ProductCategorySet', response.data);
                });
        },
        // оплата бара
        Pay({
            commit,
            state
        }, arr) {
            let data = {
                order_id: arr.order_id,
                print: arr.print,
                cart: state.cart,
                user: state.user,
                skidka: state.skidka,
                billing: arr.billing,
                info: $('#comment').val()
            };
            return axios.post('/order/Pay', data)
                .then(response => {
                    if (response.data.success) {
                        location.href = '/open-bar?status=1';
                        commit('ErrorCount', [])
                    } else {
                        commit('ErrorCount', response.data.ErrorCount)
                    }

                })
                .catch(error => {
                    alert(error.response)
                })

        },

        // оплата стола
        PayTable({
            commit,
            state
        }, arr) {

            let index = state.tables.map(item => parseInt(item.id)).indexOf(state.TableCloseActive);
            let order_id = state.tables[index].order_id;
            let data = {
                order_id: order_id,
                print: arr.print,
                user: state.user,
                billing: arr.billing,
                info: $('#comment').val()
            };

            return axios.post('/order/PayTable', data)
                .then(response => {
                    if (response.data.success) {
                        commit('ErrorCount', [])
                        commit('SetTableactive', false)
                    } else {
                        commit('ErrorCount', response.data.ErrorCount)
                    }
                })
                .catch(error => {
                    // alert(error.response)
                })
                .finally(function () {
                    // location.href = '/close-table';
                });
        },
        //пользователи
        GetUsers({
            commit,
            state
        }) {

            return axios.post('/order/GetUsers')
                .then(response => {
                    commit('UsersSet', response.data.users);
                })
                .catch(error => {
                    alert(error.response)
                })

        },
        // отправка смс или звонка
        SendSMS({
            commit,
            state
        }, data) {
            let ob = {
                typesms: data.typesms,
                phones: data.phones,
                ajaxmy: true
            }
            commit('UserSet', data.user);
            return axios.post('/generateCode', ob)
                .then(response => {
                    commit('SetSMS', response.data.id_sms)
                })
                .catch(error => {

                })

        },
        // проверка кода
        checkCode({
            commit,
            state
        }, data) {
            let i = state.users.map(item => item.value).indexOf(state.user.value);
            data.user = state.users[i].value;
            var TableOrder = false;
            if ('table' == data.order_id) {
                let index = state.tables.map(item => parseInt(item.id)).indexOf(state.TableCloseActive);
                data.order_id = state.tables[index].order_id;
                TableOrder = true;
            }
            return axios.post('/checkCode', data)
                .then(response => {
                    if (TableOrder) {
                        commit('checkCode', response.data)
                    } else {
                        commit('checkCode', response.data)
                        if (state.typeOrder == "type_bar") {
                            commit('SetTotal');
                        }
                    }
                    setTimeout(() => {
                        $('#SmsCode').modal('hide');
                    }, 300)

                })
                .catch(error => {})
        },
        // получение продуктов первончально при загрузке страницы
        getOrder({
            commit,
            state
        }, order_id) {
            return axios.post('/order/getOrder', {
                    order_id: order_id
                })
                .then(response => {
                    commit('SetCart', response.data);
                    if (response.data.typeOrder == "type_bar") {
                        commit('SetTotal');
                    }
                })
                .catch(error => {

                })
        },
        // запрос цены открытого стола
        getTablePrice({
            commit,
            state
        }, order_id) {
            return axios.get('/ajax/priceorder' + '?order_id=' + order_id)
                .then((response) => {
                    commit('SetTotalTable', response.data.results)
                })
        },
        // при добавлении сохранение продуктов
        setReserveAndCart({
            commit,
            state
        }, data) {
            let carts = {
                cart: state.cart,
                order_id: data.order_id,
                user: state.user,
                skidka: state.skidka,
            };
            return axios.post('/order/Reserve', carts)
                .then(response => {

                })
                .catch(error => {

                })
        },

        // получить  столы
        getTables({
            commit,
            state
        }) {
            return axios.get('/table/getTables')
                .then((response) => {
                    commit('SetTables', response.data.tables)
                })
        }

    }
};

export default store;