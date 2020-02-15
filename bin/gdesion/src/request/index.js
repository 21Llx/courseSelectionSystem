export const myAxios = (that, dates = {}, type = 'post') => {
  return new Promise(function (resovle, reject) {
    that.$axios
      .post("http://127.0.0.1/courseSelectionSystem/src/index.php", that.$qs.stringify(dates),
        { headers: { "Content-Type": "application/x-www-form-urlencoded" } })
      .then(function (res) {
        resovle(res.data)
      });
  })
}