const TOKEN_KEY = 'ui_token'
const TENANT_KEY = 'ui_tenant'
const DEVICE = 'web-spa'

export const auth = {
  get token() { return localStorage.getItem(TOKEN_KEY) || '' },
  set token(v) { localStorage.setItem(TOKEN_KEY, v || '') },
  clear() { localStorage.removeItem(TOKEN_KEY) },
  isAuthenticated() { return !!localStorage.getItem(TOKEN_KEY) },

  get tenant() { return localStorage.getItem(TENANT_KEY) || '' },
  set tenant(v) { localStorage.setItem(TENANT_KEY, v || '') },
  hasTenant() { return !!localStorage.getItem(TENANT_KEY) },

  device: DEVICE
}
