import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { getCsrfHeader } from '../utils/headerhelper';

const sendRequest = (item, type, ingredients, setIngredients) => {
    if (!item.description || !item.name ) return;
    if (type === DELETE) {
        const confirm = window.confirm(`Are you sure you want to delete ${item.name}`);
        if (!confirm) return;
    }
    const data = {
        method: type,
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            ...getCsrfHeader(),
        },
    };

    let endpoint;
    if (type === ADD) {
        endpoint = '/ingredient';
    } else {
        endpoint = `/ingredient/${item.id}`;
    }

    if (type === ADD || type === EDIT) {
        const { name, description } = item;
        data.body = JSON.stringify({
            name, description,
        });
    }

    fetch(endpoint, data)
        .then(resp => {
            if (resp.status !== 200) {
                throw new Error('bad request');
            }
            return resp.json();
        })
        .then(data => {
            if (type === DELETE) {
                setIngredients(ingredients.filter(i => i.id !== data.drinkDeleted));
            } else if (type === EDIT) {
                window.$('#addEditIngredient').modal('hide');
                setIngredients(ingredients.map(i => {
                    if (i.id !== data.drinkUpdated.id) {
                        return i;
                    } else {
                        const { id, name, description, image } = data.drinkUpdated;
                        return {
                            id,
                            name,
                            description,
                            imagegetDrink
                        }
                    }
                }));
            } else if (type === ADD) {
                window.$('#addEditIngredient').modal('hide');
                setIngredients(ingredients => [...ingredients, data.drinkAdded]);
            } else {
                throw new Error('something is bad');
            }
        });
};

const DELETE = 'DELETE';
const ADD = 'POST';
const EDIT = 'PUT';

const UNSELECTED = Object.freeze({
    id: null,
    name: '',
    description: '',
});

const Ingredients = (options) => {
    const [selectedingredient, setSelectedIngredient] = useState(UNSELECTED);
    const [ingredients, setIngredients] = useState(options.ingredients);

    useEffect(() => {
        $('#addEditIngredient').on('hidden.bs.modal', function () {
            setSelectedIngredient(UNSELECTED);
        });
    }, []);

    return (
        <div className="container">
            <div className="row">
                <table className="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Image</th>
                            <th scope="col">Delete</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {ingredients.map((ingredient) => (
                            <tr key={ ingredient.id }>
                                <td>{ingredient.id}</td>
                                <td>
                                    <a href="#">
                                        {ingredient.name}
                                    </a>
                                </td>
                                <td>{ingredient.description}</td>
                                <td>{ingredient.image}</td>
                                <td>
                                    <svg style={{ cursor: 'pointer' }}
                                        onClick={(e) => {
                                            sendRequest(
                                                ingredient,
                                                DELETE,
                                                ingredients,
                                                setIngredients,
                                            );
                                        }} className="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fillRule="evenodd" d="M2.5 1a1 1 0 00-1 1v1a1 1 0 001 1H3v9a2 2 0 002 2h6a2 2 0 002-2V4h.5a1 1 0 001-1V2a1 1 0 00-1-1H10a1 1 0 00-1-1H7a1 1 0 00-1 1H2.5zm3 4a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7a.5.5 0 01.5-.5zM8 5a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7A.5.5 0 018 5zm3 .5a.5.5 0 00-1 0v7a.5.5 0 001 0v-7z" clipRule="evenodd"/>
                                    </svg>
                                </td>
                                <td>
                                    <button type="button" className="btn btn-dark btn-sm" onClick={(e) => {
                                        window.$('#addEditIngredient').modal('show');
                                        const {id, name, description} = ingredient;
                                        setSelectedIngredient({ id, name, description });
                                    }}>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            <button type="button" className="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditIngredient">
              Add Ingredient
            </button>

            <div className="modal fade" id="addEditIngredient" tabIndex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered" role="document">
                    <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title" id="exampleModalLongTitle">{selectedingredient.id === null ? 'Add Ingredient' : 'Edit Ingredient'}</h5>
                        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="modal-body">
                    <div className="form-group">
                        <label htmlFor="ingredientName">Ingredient Name</label>
                            <input type="text" className="form-control" id="ingredientName" placeholder="Elderflower Liqueur"
                                value={selectedingredient.name}
                                onChange={(e) => {
                                    setSelectedIngredient({
                                        ...selectedingredient,
                                        name: e.target.value,
                                    });
                                }}
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="ingredientDescription">Ingredient Description</label>
                            <input type="email" className="form-control" id="ingredientDescription" placeholder="Good stuff"
                                value={selectedingredient.description}
                                onChange={(e) => {
                                    setSelectedIngredient({
                                        ...selectedingredient,
                                        description: e.target.value,
                                    });
                                }}
                            />
                        </div>
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" className="btn btn-primary"
                            onClick={(e) => {
                                if (selectedingredient.id === null) {
                                    sendRequest(
                                        selectedingredient,
                                        ADD,
                                        ingredients,
                                        setIngredients
                                    );
                                } else {
                                    sendRequest(
                                        selectedingredient,
                                        EDIT,
                                        ingredients,
                                        setIngredients
                                    );
                                }
                            }}
                        >
                            {selectedingredient.id === null ? 'Add Ingredient' : 'Update Ingredient'}
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Ingredients;

if (document.getElementById('ingredients')) {
    const bootstrapData = window.bootstrapData || {};
    ReactDOM.render(<Ingredients ingredients={bootstrapData} />, document.getElementById('ingredients'));
}
