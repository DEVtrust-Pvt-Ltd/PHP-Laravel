import axios from "axios";

const ListsManager =  {
    _urls: [],

    fetchField(field, input) {
        function _fetchValueFromList(data, input) {
            const _x = data.find(el => (el.value === input || "" + el.value === "" + input) );
            if(_x) {
                return _x.title;
            } else {
                return "ukn["+input+"]";
            }
        }
        return new Promise((resolve, reject) => {
            if(field.source.data) {
                return resolve({value: _fetchValueFromList(field.source.data, input)});
            } else if(field.source.url) {
                const _listener = data => {
                    return resolve({value: _fetchValueFromList(data, input)});
                };

                let _url = this._urls.find(el => el.url === field.source.url);
console.log(_url);
                if(!_url) {
                    _url = {
                        url: field.source.url,
                        status: 0,
                        data: null,
                        listeners: [_listener],
                    };
                    this._urls.push(_url);

                    axios.get(field.source.url).then(response => {
                        _url.data = response.data;
                        _url.status = 1;
                        _url.listeners.forEach(x => {
                            x(response.data);
                        });
                    });
                } else {
                    if(0 === _url.status) {
                        _url.listeners.push(_listener);
                    } else {
console.log("HERE???", input);
                        return resolve({value: _fetchValueFromList(_url.data, input)});
                    }
                }
            } else if(field.source.callback) {
                Promise.resolve(field.source.callback()).then(response => {
                    let data = response;
                    if(field.source.mapper) {
                        data = data.map(field.source.mapper);
                    }
console.log("X", data );
                    return resolve({value: _fetchValueFromList(data, input)});
                });
            } else {
                return reject("Insufficient source information");
            }
        });
    }
};

export default ListsManager;
