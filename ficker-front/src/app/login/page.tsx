import styles from "./login.module.scss";
import Image from "next/image";

export default function Login() {
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Image src="/logo.png" alt="Logo" width={130} height={30} />
      </div>
      <div className={styles.container}>
        <form className={styles.form}>
          <h3 style={{ textAlign: "center" }}>Entrar</h3>
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
          <div style={{ display: "flex", justifyContent: "center" }}>
            <button type="submit" className={styles.button}>
              Entrar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
