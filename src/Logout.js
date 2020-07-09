import React from 'react';

import {unauthenticate} from './Auth';

export default class Logout extends React.Component {
    componentDidMount() {
        unauthenticate();

        this.props.history.push('/');
    }

    render() {return '';}
}