export const baseUrl = '/api'

export const app = {
    name: 'FindYourVet.ph'
}

export const userTypes = {
    SUPER: -1,
    ADMIN: 0,
    VET: 1,
    PET_OWNER: 2,
    '-1': 'SUPER',
    '0': 'ADMIN',
    '1': 'VETERINARIAN',
    '2': 'PET_OWNER'
}

export default {
    baseUrl,
    app,
    userTypes
}
