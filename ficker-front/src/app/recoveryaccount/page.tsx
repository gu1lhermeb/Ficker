import Image from "next/image";
import styles from "./recoveryaccount.module.scss";
import Link from "next/link";

const RecoveryAccount = () => {
  return (
    <div>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div className={styles.container}>
        <div className={styles.content}>
          <h2 style={{ textAlign: "center", marginBottom: 50, fontSize: 22}}>Esqueceu sua senha?</h2>
          <label className={styles.label} style={{ marginBottom: 5 }} htmlFor="email">
            E-mail
          </label>
          <input className={styles.input} id="email" type="email" />
          <div style={{ display: "flex", justifyContent: "center" }}>
            <button className={styles.button}>Enviar</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RecoveryAccount;
