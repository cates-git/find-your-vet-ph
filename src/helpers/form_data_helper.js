export function objectToformData (object) {
    var data = new FormData()
    Object.keys(object).forEach((key) => {
        let value = object[key] && object[key].hasOwnProperty('value') ? object[key].value : object[key]
        if (value) {
            data.append(key, value)
        }
    })
    return data
}

export function validateForm (form) {

    let willProceed = true
    let data = new FormData()

    Object.keys(form).forEach((key) => {
        let value = form[key].value
        let required = form[key].hasOwnProperty('required') ? form[key].required : true
        if (!value && required) {
            form[key].error  = true
            willProceed = false
        } else {
            form[key].error  = false
            data.append(key, value)
        }
    })

    if (!willProceed) {
        return false
    }
    
    return data
}

export function clearForm (form) {
    Object.keys(form).forEach((key) => {
       form[key].value = null
       form[key].isEmpty  = false
    })
}