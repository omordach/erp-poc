import { auth } from '../store/auth'

const base = '/api/v1'

async function http(method, path, { params, body, headers } = {}) {
  const url = new URL(base + path, window.location.origin)
  if (params) Object.entries(params).forEach(([k, v]) => url.searchParams.set(k, v))

  const h = { 'Content-Type': 'application/json' }
  if (auth.token) h['Authorization'] = `Bearer ${auth.token}`
  if (auth.tenant) h['X-Tenant'] = auth.tenant
  Object.assign(h, headers || {})

  const res = await fetch(url, {
    method,
    headers: h,
    body: body ? JSON.stringify(body) : undefined
  })

  const isJson = res.headers.get('content-type')?.includes('application/json')
  const data = isJson ? await res.json() : await res.text()

  if (!res.ok) {
    const message = (isJson && data?.error?.message) || res.statusText
    throw new Error(`${res.status} ${message}`)
  }
  return data
}

export const api = {
  login(email, password) {
    return http('POST', '/auth/token', {
      body: { email, password, device_name: auth.device }
    })
  },

  // Membership
  getUnions(params) { return http('GET', '/unions', { params }) },
  createUnion(payload) { return http('POST', '/unions', { body: payload }) },
  getLocals(params) { return http('GET', '/locals', { params }) },
  createLocal(payload) { return http('POST', '/locals', { body: payload }) },
  getMembers(params) { return http('GET', '/members', { params }) },
  createMember(payload) { return http('POST', '/members', { body: payload }) },

  // Grievances
  getGrievances(params) { return http('GET', '/grievances', { params }) },
  createGrievance(payload) { return http('POST', '/grievances', { body: payload }) },

  // Events
  getEvents(params) { return http('GET', '/events', { params }) },
  createEvent(payload) { return http('POST', '/events', { body: payload }) },

  // Payments
  getInvoices(params) { return http('GET', '/invoices', { params }) },
  createInvoice(payload) { return http('POST', '/invoices', { body: payload }) },
  getPayments(params) { return http('GET', '/payments', { params }) },
  createPayment(payload) { return http('POST', '/payments', { body: payload }) },
}
