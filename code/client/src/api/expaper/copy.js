import get from './get'

export default async (store, id) => {
  await get(store, id)
  store.infoList.paper.renew()
}