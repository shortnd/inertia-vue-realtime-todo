<template>
    <layout>
        <h2>todos</h2>
        <div>
            <input type="text" v-model="newTodo.title" @keyup.enter="addTodo" autofocus="autofocus" placeholder="What are you trying to get done?" class="new-todo">
        </div>
        <ul v-if="tasks" v-for="task in tasks" class="todo-list" :key="task.id">
            <li
                class="todo"
                :class="{ completed: task.completed }">
                <div class="view">
                    <input type="checkbox" v-model="task.completed" class="toggle">
                    <label>{{ task.title }}</label>
                    <button @click="removeTodo(task)" class="destroy"></button>
                </div>
            </li>
        </ul>
    </layout>
</template>

<script>
import Layout from '@/Shared/Layout'
import { Inertia } from 'inertia-vue'
import axios from 'axios'

export default {
    props: ['tasks'],
    components: {
        Layout
    },
    data() {
        return {
            newTodo: {
                title: null,
            }
        }
    },
    mounted() {
        window.Echo.channel("newTask").listen(".task-created", e => {
            Inertia.visit('/todos')
        });
        window.Echo.channel("taskRemoved").listen(".task-removed", e => {
            Inertia.visit("/todos")
        })
    },
    methods: {
        addTodo() {
            // console.log(this.newTodo)
            axios.post('/todos', this.newTodo)
            .then(() => {
                this.newTodo.title = null
            })
            .catch(({ response }) => {
                console.log(response.data.errors)
            })
        },
        removeTodo(task) {
            axios.delete(`/todos/${task.id}`)
                .then(() => {
                    // console.log('deleted')
                }).catch(({ response }) => console.log(response.data.errors))
        }
    }
}
</script>

<style>
html, body {
                    background-color: #fff;
                    color: #636b6f;
                    font-family: 'Nunito', sans-serif;
                    font-weight: 200;
                    height: 100vh;
                    margin: 0;
                }
                .full-height {
                    height: 100vh;
                }
                .flex-center {
                    align-items: center;
                    display: flex;
                    justify-content: center;
                }
                .position-ref {
                    position: relative;
                }
                .top-right {
                    position: absolute;
                    right: 10px;
                    top: 18px;
                }
                .content {
                    text-align: center;
                }
                .title {
                    font-size: 84px;
                }
                .links > a {
                    color: #636b6f;
                    padding: 0 25px;
                    font-size: 12px;
                    font-weight: 600;
                    letter-spacing: .1rem;
                    text-decoration: none;
                    text-transform: uppercase;
                }
                .m-b-md {
                    margin-bottom: 30px;
                }
</style>
