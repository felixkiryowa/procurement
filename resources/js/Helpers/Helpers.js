import axios from 'axios';


export default class Helper {
    static logoutUser() {
        axios.post('/user/logout').then(response => {
            window.location.replace('/');
        }).catch(error => {

        });
    }
}