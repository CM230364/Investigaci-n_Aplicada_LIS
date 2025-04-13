import React from 'react';
import ReactDOM from 'react-dom';

class App extends React.Component {
    state = {
        contacts: [],
        formData: { nombre: '', email: '', mensaje: '' }
    };

    componentDidMount() {
        this.fetchContacts();
    }

    fetchContacts = () => {
        fetch('/data')
            .then(res => res.json())
            .then(contacts => this.setState({ contacts }));
    };

    handleInputChange = (e) => {
        this.setState({
            formData: {
                ...this.state.formData,
                [e.target.name]: e.target.value
            }
        });
    };

    handleSubmit = (e) => {
        e.preventDefault();
        fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.state.formData)
        })
        .then(() => {
            this.fetchContacts();
            this.setState({
                formData: { nombre: '', email: '', mensaje: '' }
            });
        });
    };

    render() {
        return (
            <div className="app">
                <h1>Bienvenido a Empresa X</h1>
                
                <section>
                    <h2>Contacto</h2>
                    <form onSubmit={this.handleSubmit}>
                        <input 
                            name="nombre" 
                            placeholder="Nombre"
                            value={this.state.formData.nombre}
                            onChange={this.handleInputChange}
                            required
                        />
                        <input 
                            name="email" 
                            type="email"
                            placeholder="Email"
                            value={this.state.formData.email}
                            onChange={this.handleInputChange}
                            required
                        />
                        <textarea 
                            name="mensaje" 
                            placeholder="Mensaje"
                            value={this.state.formData.mensaje}
                            onChange={this.handleInputChange}
                            required
                        />
                        <button type="submit">Enviar</button>
                    </form>
                </section>
                
                <section>
                    <h2>Contactos Registrados</h2>
                    <ul>
                        {this.state.contacts.map(contact => (
                            <li key={contact.id}>
                                <strong>{contact.nombre}</strong> - {contact.email}
                                <p>{contact.mensaje}</p>
                            </li>
                        ))}
                    </ul>
                </section>
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));