import {post} from '../../utils/request'

export default async store => await post('api/score',store.infoList.exam_paper.cheat())