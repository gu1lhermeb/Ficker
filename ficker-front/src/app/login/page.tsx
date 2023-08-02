import styles from "./login.module.scss";
import Image from "next/image";
import Link from "next/link";

export default function Login() {
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div className={styles.container}>
        <form className={styles.form}>
          <h2 style={{ textAlign: "center" }}>Entrar</h2>
          <label htmlFor="email" style={{ marginBottom: 5 }}>
            Email
          </label>
          <input type="email" id="email" required className={styles.input} />
          <label htmlFor="password" style={{ marginBottom: 5 }}>
            Senha
          </label>
          <input
            type="password"
            id="password"
            required
            className={styles.input}
          />
          <div
            style={{
              display: "flex",
              justifyContent: "center",
              flexDirection: "column",
              alignItems: "center",
            }}
          >
            <button type="submit" className={styles.button}>
              Entrar
            </button>
            <Link href={"/"} style={{ textDecoration: 'none' }}>
              <p style={{ fontSize: 14, marginTop: 20, color: 'black'}}>Esqueceu a senha?</p>
            </Link>
            <Link href={"/createaccount"} style={{ textDecoration: 'none' }}>
              <p style={{ fontSize: 14, marginTop: -7, color: 'black'}}>Cadastre-se</p>
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
}
