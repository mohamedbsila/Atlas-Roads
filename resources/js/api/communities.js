// Community API client

let csrfPromise = null;

export function ensureCsrfCookie() {
  if (!csrfPromise) {
    csrfPromise = window.axios.get('/sanctum/csrf-cookie');
  }
  return csrfPromise;
}

export async function listCommunities(params = {}) {
  const response = await window.axios.get('/api/communities', { params });
  return response.data;
}

export async function getCommunity(communityId) {
  const response = await window.axios.get(`/api/communities/${communityId}`);
  return response.data;
}

export async function createCommunity(payload) {
  await ensureCsrfCookie();
  const response = await window.axios.post('/api/communities', payload);
  return response.data;
}

export async function updateCommunity(communityId, payload) {
  await ensureCsrfCookie();
  const response = await window.axios.put(`/api/communities/${communityId}`, payload);
  return response.data;
}

export async function deleteCommunity(communityId) {
  await ensureCsrfCookie();
  const response = await window.axios.delete(`/api/communities/${communityId}`);
  return response.data;
}

export async function joinCommunity(communityId) {
  await ensureCsrfCookie();
  const response = await window.axios.post(`/api/communities/${communityId}/join`);
  return response.data;
}

export async function leaveCommunity(communityId) {
  await ensureCsrfCookie();
  const response = await window.axios.post(`/api/communities/${communityId}/leave`);
  return response.data;
}
