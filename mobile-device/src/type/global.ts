export interface ICoordinate {
  latitude: number,
  longitude: number
}



export interface IResponseAbsen {
  id_siswa: string,
  tgl_absen: string,
  jam: string,
  jarak: string,
  tipe_absen: number,

}

export interface IResponseStatusAbsen {
  absensi: {
    id_siswa: string,
    nama: string,
    tgl_absen: string,
    jam_datang: string,
    jarak_datang: string,
    jam_pulang: string,
    jarak_pulang: string,
    tipe_absen: number,
    keterangan: string
  },
  pengaturan: ICoordinate
}

export interface IUser {
  username: string,
  nama: string,
  jenis_kelamin: string,
  jenis_user: string,
  access_token: string,
  refresh_token: string,
}

export interface IResponseLogin {
  success: string,
  message: string,
  data: IUser,
}




