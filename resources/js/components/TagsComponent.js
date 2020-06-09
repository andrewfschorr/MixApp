import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import { getCsrfHeader } from '../utils/headerhelper';

const sendRequest = (item, type, tags, setTags) => {
    if (!item.name ) return;
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
        endpoint = '/tag';
    } else {
        endpoint = `/tag/${item.id}`;
    }

    if (type === ADD || type === EDIT) {
        const { name } = item;
        data.body = JSON.stringify({
            name
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
                setTags(tags.filter(i => i.id !== data.tagDeleted));
            } else if (type === EDIT) {
                window.$('#addEditTag').modal('hide');
                setTags(tags.map(i => {
                    if (i.id !== data.tagUpdated.id) {
                        return i;
                    } else {
                        const { id, name } = data.tagUpdated;
                        return {
                            id,
                            name,
                        }
                    }
                }));
            } else if (type === ADD) {
                window.$('#addEditTag').modal('hide');
                setTags(tags => [...tags, data.tagAdded]);
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
});

const Tags = (options) => {
    const [selectedTag, setSelectedTag] = useState(UNSELECTED);
    const [tags, setTags] = useState(options.tags);

    useEffect(() => {
        $('#addEditTag').on('hidden.bs.modal', function () {
            setSelectedTag(UNSELECTED);
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
                            <th scope="col">Delete</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {tags.map((tag) => (
                            <tr key={tag.id }>
                                <td>{tag.id}</td>
                                <td>
                                    <a href="#">
                                        {tag.name}
                                    </a>
                                </td>
                                <td>
                                    <svg style={{ cursor: 'pointer' }}
                                        onClick={(e) => {
                                            sendRequest(
                                                tag,
                                                DELETE,
                                                tags,
                                                setTags,
                                            );
                                        }} className="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fillRule="evenodd" d="M2.5 1a1 1 0 00-1 1v1a1 1 0 001 1H3v9a2 2 0 002 2h6a2 2 0 002-2V4h.5a1 1 0 001-1V2a1 1 0 00-1-1H10a1 1 0 00-1-1H7a1 1 0 00-1 1H2.5zm3 4a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7a.5.5 0 01.5-.5zM8 5a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7A.5.5 0 018 5zm3 .5a.5.5 0 00-1 0v7a.5.5 0 001 0v-7z" clipRule="evenodd"/>
                                    </svg>
                                </td>
                                <td>
                                    <button type="button" className="btn btn-dark btn-sm" onClick={(e) => {
                                        window.$('#addEditTag').modal('show');
                                        const {id, name} = tag;
                                        setSelectedTag({ id, name });
                                    }}>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            <button type="button" className="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEditTag">
              Add Tag
            </button>

            <div className="modal fade" id="addEditTag" tabIndex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div className="modal-dialog modal-dialog-centered" role="document">
                    <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title" id="exampleModalLongTitle">{selectedTag.id === null ? 'Add Tag' : 'Edit Tag'}</h5>
                        <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="modal-body">
                    <div className="form-group">
                        <label htmlFor="ingredientName">Tag Name</label>
                            <input type="text" className="form-control" id="ingredientName" placeholder="Boozy"
                                value={selectedTag.name}
                                onChange={(e) => {
                                    setSelectedTag({
                                        ...selectedTag,
                                        name: e.target.value,
                                    });
                                }}
                            />
                        </div>
                    </div>
                    <div className="modal-footer">
                        <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" className="btn btn-primary"
                            onClick={(e) => {
                                if (selectedTag.id === null) {
                                    sendRequest(
                                        selectedTag,
                                        ADD,
                                        tags,
                                        setTags
                                    );
                                } else {
                                    sendRequest(
                                        selectedTag,
                                        EDIT,
                                        tags,
                                        setTags
                                    );
                                }
                            }}
                        >
                            {selectedTag.id === null ? 'Add Tag' : 'Update Tag'}
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Tags;

if (document.getElementById('tags')) {
    const bootstrapData = window.bootstrapData || {};
    ReactDOM.render(<Tags tags={bootstrapData} />, document.getElementById('tags'));
}
